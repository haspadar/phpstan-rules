<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\StatementCountRule;

use Haspadar\PHPStanRules\Rules\StatementCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<StatementCountRule> */
final class StatementCountRuleNewSyntaxTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StatementCountRule(4);
    }

    #[Test]
    public function passesWhenStatementsAreInsidePropertyHooks(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithPropertyHooks.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenStatementsAreInsidePipeArrowFunctions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithPipeOperator.php'],
            [],
        );
    }
}
