<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\IllegalCatchRule;

use Haspadar\PHPStanRules\Rules\IllegalCatchRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<IllegalCatchRule> */
final class IllegalCatchRuleCustomNamesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new IllegalCatchRule(['DatabaseException']);
    }

    #[Test]
    public function reportsErrorForCustomIllegalType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithCustomIllegalCatch.php'],
            [
                ['Catching DatabaseException is not allowed.', 13],
            ],
            'Custom illegal class name should trigger violation',
        );
    }

    #[Test]
    public function passesForDefaultTypeWhenNotInCustomList(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithBroadExceptionCatch.php'],
            [],
            'Exception should pass when not in custom illegal list',
        );
    }
}
