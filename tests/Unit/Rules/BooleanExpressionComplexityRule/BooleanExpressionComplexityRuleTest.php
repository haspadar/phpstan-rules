<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\BooleanExpressionComplexityRule;

use Haspadar\PHPStanRules\Rules\BooleanExpressionComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<BooleanExpressionComplexityRule> */
final class BooleanExpressionComplexityRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new BooleanExpressionComplexityRule(3);
    }

    #[Test]
    public function passesWhenOperatorsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/SimpleMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenOperatorsExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/ComplexMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule\ComplexMethod::run() has boolean expression complexity of 4. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/ExactMethod.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/SuppressedMethod.php'],
            [],
        );
    }
}
