<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\SimplifyBooleanExpressionRule;

use Haspadar\PHPStanRules\Rules\SimplifyBooleanExpressionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<SimplifyBooleanExpressionRule> */
final class SimplifyBooleanExpressionRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new SimplifyBooleanExpressionRule();
    }

    #[Test]
    public function reportsComparisonWithTrue(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SimplifyBooleanExpressionRule/CompareWithTrue.php'],
            [
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    11,
                ],
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    15,
                ],
            ],
        );
    }

    #[Test]
    public function reportsComparisonWithFalse(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SimplifyBooleanExpressionRule/CompareWithFalse.php'],
            [
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    11,
                ],
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    15,
                ],
            ],
        );
    }

    #[Test]
    public function reportsNotEqualBooleanComparisons(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SimplifyBooleanExpressionRule/NotEqualBoolean.php'],
            [
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    11,
                ],
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    15,
                ],
            ],
        );
    }

    #[Test]
    public function reportsBooleanLiteralOnLeftSide(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SimplifyBooleanExpressionRule/BooleanLiteralOnLeft.php'],
            [
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    11,
                ],
                [
                    'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
                    15,
                ],
            ],
        );
    }

    #[Test]
    public function passesForValidComparisons(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SimplifyBooleanExpressionRule/ValidComparisons.php'],
            [],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SimplifyBooleanExpressionRule/SuppressedClass.php'],
            [],
        );
    }
}
