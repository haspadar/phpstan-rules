<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CognitiveComplexityRule;

use Haspadar\PHPStanRules\Rules\CognitiveComplexityRule;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/** Tests that CognitiveComplexityRule rejects invalid configuration */
final class CognitiveComplexityRuleValidationTest extends TestCase
{
    #[Test]
    public function throwsWhenMaxComplexityIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxComplexity must be a positive integer');

        (fn() => new CognitiveComplexityRule(0))();
    }

    #[Test]
    public function throwsWhenMaxComplexityIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('maxComplexity must be a positive integer');

        (fn() => new CognitiveComplexityRule(-1))();
    }
}
