<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\StatementCountRule;

use Haspadar\PHPStanRules\Rules\StatementCountRule;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class StatementCountRuleValidationTest extends TestCase
{
    #[Test]
    public function throwsWhenMaxStatementsIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new StatementCountRule(0);
    }

    #[Test]
    public function throwsWhenMaxStatementsIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new StatementCountRule(-1);
    }
}
