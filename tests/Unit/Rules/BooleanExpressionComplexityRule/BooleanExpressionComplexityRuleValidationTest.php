<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\BooleanExpressionComplexityRule;

use Haspadar\PHPStanRules\Rules\BooleanExpressionComplexityRule;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/** Tests that BooleanExpressionComplexityRule rejects invalid configuration */
final class BooleanExpressionComplexityRuleValidationTest extends TestCase
{
    #[Test]
    public function throwsWhenMaxOperatorsIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxOperators must be a positive integer');

        (fn() => new BooleanExpressionComplexityRule(0))();
    }

    #[Test]
    public function throwsWhenMaxOperatorsIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxOperators must be a positive integer');

        (fn() => new BooleanExpressionComplexityRule(-1))();
    }
}
