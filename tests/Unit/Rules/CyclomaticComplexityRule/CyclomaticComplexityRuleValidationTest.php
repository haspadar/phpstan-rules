<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CyclomaticComplexityRule;

use Haspadar\PHPStanRules\Rules\CyclomaticComplexityRule;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/** Tests that CyclomaticComplexityRule rejects invalid configuration */
final class CyclomaticComplexityRuleValidationTest extends TestCase
{
    #[Test]
    public function throwsWhenMaxComplexityIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (fn() => new CyclomaticComplexityRule(0))();
    }

    #[Test]
    public function throwsWhenMaxComplexityIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (fn() => new CyclomaticComplexityRule(-1))();
    }
}
