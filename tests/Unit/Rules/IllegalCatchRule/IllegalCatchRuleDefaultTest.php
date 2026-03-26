<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\IllegalCatchRule;

use Haspadar\PHPStanRules\Rules\IllegalCatchRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<IllegalCatchRule> */
final class IllegalCatchRuleDefaultTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new IllegalCatchRule();
    }

    #[Test]
    public function reportsErrorForExceptionByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithBroadExceptionCatch.php'],
            [
                ['Catching Exception is not allowed.', 13],
            ],
            'Default list must include Exception',
        );
    }

    #[Test]
    public function reportsErrorForThrowableByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithThrowableCatch.php'],
            [
                ['Catching Throwable is not allowed.', 13],
            ],
            'Default list must include Throwable',
        );
    }

    #[Test]
    public function reportsEachViolationIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalCatchRule/ClassWithMultipleBroadCatches.php'],
            [
                ['Catching RuntimeException is not allowed.', 13],
                ['Catching Error is not allowed.', 19],
            ],
            'Each broad catch in the method should produce its own error',
        );
    }
}
