<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ModifiedControlVariableRule;

use Haspadar\PHPStanRules\Rules\ModifiedControlVariableRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ModifiedControlVariableRule> */
final class ModifiedControlVariableRulePassesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ModifiedControlVariableRule();
    }

    #[Test]
    public function passesWhenNoModification(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithNoModification.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenForHasNoInitVariable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithEmptyForInit.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenForHasNoInitAssignment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithForNoInitAssign.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenArrayElementModifiedInForLoop(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithNonVariableModification.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenOtherVarModifiedInForLoop(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithOtherVarModifiedInForLoop.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenClosureInsideForModifiesSameNamedVar(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ModifiedControlVariableRule/ClassWithClosureInForLoop.php'],
            [],
        );
    }
}
