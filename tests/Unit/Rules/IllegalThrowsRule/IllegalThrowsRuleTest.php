<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\IllegalThrowsRule;

use Haspadar\PHPStanRules\Rules\IllegalThrowsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<IllegalThrowsRule> */
final class IllegalThrowsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new IllegalThrowsRule(['RuntimeException']);
    }

    #[Test]
    public function passesWhenThrowsSpecificType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithCleanThrows.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForBroadRuntimeExceptionThrows(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithBroadRuntimeExceptionThrows.php'],
            [
                ['Throwing RuntimeException is not allowed.', 10],
            ],
        );
    }

    #[Test]
    public function reportsOnlyIllegalTypeInMixedThrows(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithMixedThrows.php'],
            [
                ['Throwing RuntimeException is not allowed.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithNoThrowsAnnotation.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/SuppressedClassWithBroadThrows.php'],
            [],
        );
    }
}
