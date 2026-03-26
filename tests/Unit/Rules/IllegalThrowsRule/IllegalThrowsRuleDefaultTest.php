<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\IllegalThrowsRule;

use Haspadar\PHPStanRules\Rules\IllegalThrowsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<IllegalThrowsRule> */
final class IllegalThrowsRuleDefaultTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new IllegalThrowsRule();
    }

    #[Test]
    public function reportsRuntimeExceptionByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithBroadRuntimeExceptionThrows.php'],
            [
                ['Throwing RuntimeException is not allowed.', 10],
            ],
        );
    }

    #[Test]
    public function reportsThrowableByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithMultipleBroadThrows.php'],
            [
                ['Throwing RuntimeException is not allowed.', 10],
                ['Throwing Throwable is not allowed.', 11],
            ],
        );
    }

    #[Test]
    public function reportsEachViolationIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithMultipleBroadThrows.php'],
            [
                ['Throwing RuntimeException is not allowed.', 10],
                ['Throwing Throwable is not allowed.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorTypeByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithBroadErrorThrows.php'],
            [
                ['Throwing Error is not allowed.', 10],
            ],
        );
    }

    #[Test]
    public function passesForOverriddenMethodByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithOverriddenMethodThrows.php'],
            [],
        );
    }
}
