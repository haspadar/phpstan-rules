<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ReturnCountRule;

use Haspadar\PHPStanRules\Rules\ReturnCountRule;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/** Validates that ReturnCountRule rejects invalid constructor arguments */
final class ReturnCountRuleValidationTest extends TestCase
{
    #[Test]
    public function throwsWhenMaxIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ReturnCountRule(0);
    }

    #[Test]
    public function throwsWhenMaxIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ReturnCountRule(-1);
    }
}
