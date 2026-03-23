<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\BooleanExpressionComplexityRule;

use Haspadar\PHPStanRules\Rules\BooleanExpressionComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<BooleanExpressionComplexityRule> */
final class BooleanExpressionComplexityRuleOperatorsTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new BooleanExpressionComplexityRule(3);
    }

    #[Test]
    public function passesWhenLogicalKeywordOperatorsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/MethodWithLogicalKeywords.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenLogicalKeywordsExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/MethodWithTooManyLogicalKeywords.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule\MethodWithTooManyLogicalKeywords::run() has boolean expression complexity of 4. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenOperatorsAreInsideNestedClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/MethodWithNestedScope.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenOperatorsAreInsideArrowFunction(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/MethodWithArrowFunction.php'],
            [],
        );
    }
}
