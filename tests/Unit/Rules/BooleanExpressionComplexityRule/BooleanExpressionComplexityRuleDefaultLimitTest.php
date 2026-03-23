<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\BooleanExpressionComplexityRule;

use Haspadar\PHPStanRules\Rules\BooleanExpressionComplexityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<BooleanExpressionComplexityRule> */
final class BooleanExpressionComplexityRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new BooleanExpressionComplexityRule();
    }

    #[Test]
    public function passesWhenExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/DefaultLimitMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BooleanExpressionComplexityRule/DefaultLimitExceeded.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\BooleanExpressionComplexityRule\DefaultLimitExceeded::run() has boolean expression complexity of 4. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }
}
