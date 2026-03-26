<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\IllegalCatchRule;

use Haspadar\PHPStanRules\Rules\IllegalCatchRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<IllegalCatchRule> */
final class IllegalCatchRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new IllegalCatchRule(['Exception']);
    }

    #[Test]
    public function passesWhenCatchUsesSpecificType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithCleanCatch.php'],
            [],
            'Specific exception type should pass',
        );
    }

    #[Test]
    public function reportsErrorForBroadExceptionCatch(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithBroadExceptionCatch.php'],
            [
                ['Catching Exception is not allowed.', 13],
            ],
            'Catching broad Exception should be reported',
        );
    }

    #[Test]
    public function reportsOnlyIllegalTypeInMixedCatch(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithMixedTypesInCatch.php'],
            [
                ['Catching Exception is not allowed.', 13],
            ],
            'Only the illegal type in a multi-type catch should be reported',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/SuppressedClassWithBroadCatch.php'],
            [],
            'Suppressed violation should not be reported',
        );
    }
}
