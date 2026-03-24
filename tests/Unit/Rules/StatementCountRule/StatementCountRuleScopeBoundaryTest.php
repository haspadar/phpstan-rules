<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\StatementCountRule;

use Haspadar\PHPStanRules\Rules\StatementCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<StatementCountRule> */
final class StatementCountRuleScopeBoundaryTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StatementCountRule(4);
    }

    #[Test]
    public function passesWhenStatementsAreInsideTwoClosures(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithTwoClosures.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenStatementsAreInsideNestedFunction(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithNestedFunction.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorCountingNestedIfStatements(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/LongMethodWithNestedIf.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule\LongMethodWithNestedIf::run() has 5 executable statements. Maximum allowed is 4.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForStatementsAfterScopeBoundary(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/LongMethodAfterNestedFunction.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule\LongMethodAfterNestedFunction::run() has 6 executable statements. Maximum allowed is 4.',
                    9,
                ],
            ],
        );
    }
}
