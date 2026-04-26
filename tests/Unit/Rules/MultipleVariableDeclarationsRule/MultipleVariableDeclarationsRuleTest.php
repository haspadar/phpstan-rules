<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MultipleVariableDeclarationsRule;

use Haspadar\PHPStanRules\Rules\MultipleVariableDeclarationsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MultipleVariableDeclarationsRule> */
final class MultipleVariableDeclarationsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MultipleVariableDeclarationsRule();
    }

    #[Test]
    public function passesWhenEachAssignmentIsOnItsOwnLine(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/SingleAssignment.php'],
            [],
        );
    }

    #[Test]
    public function reportsEveryChainedAssignmentIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/ChainedAssignment.php'],
            [
                ['Chained assignment is forbidden: split into separate statements.', 12],
                ['Chained assignment is forbidden: split into separate statements.', 14],
            ],
        );
    }

    #[Test]
    public function reportsDeepChainedAssignmentOnceAtTheOutermostLink(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/DeepChainedAssignment.php'],
            [
                ['Chained assignment is forbidden: split into separate statements.', 11],
            ],
        );
    }

    #[Test]
    public function reportsChainedNullAssignmentByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/ChainedNullAssignment.php'],
            [
                ['Chained assignment is forbidden: split into separate statements.', 11],
                ['Chained assignment is forbidden: split into separate statements.', 12],
            ],
        );
    }

    #[Test]
    public function reportsTwoStatementsOnTheSameLineAcrossEveryStatementCollection(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/MultipleStatementsPerLine.php'],
            [
                ['Only one statement is allowed per line.', 11],
                ['Only one statement is allowed per line.', 14],
                ['Only one statement is allowed per line.', 21],
                ['Only one statement is allowed per line.', 25],
                ['Only one statement is allowed per line.', 35],
            ],
        );
    }

    #[Test]
    public function reportsMultipleStatementsAtFileLevelOutsideAnyNamespace(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/FileLevelMultipleStatements.php'],
            [
                ['Only one statement is allowed per line.', 5],
            ],
        );
    }

    #[Test]
    public function reportsMultipleStatementsInsideLoopsTryCatchAndClosureBodies(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/MultipleStatementsInLoops.php'],
            [
                ['Only one statement is allowed per line.', 16],
                ['Only one statement is allowed per line.', 21],
                ['Only one statement is allowed per line.', 26],
                ['Only one statement is allowed per line.', 31],
                ['Only one statement is allowed per line.', 36],
                ['Only one statement is allowed per line.', 39],
                ['Only one statement is allowed per line.', 42],
                ['Only one statement is allowed per line.', 47],
                ['Only one statement is allowed per line.', 50],
                ['Only one statement is allowed per line.', 55],
            ],
        );
    }

    #[Test]
    public function passesForLoopWithSeveralInitExpressions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/ForLoopMultipleInits.php'],
            [],
        );
    }

    #[Test]
    public function passesAssignmentNestedInsideCondition(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/AssignInsideCondition.php'],
            [],
        );
    }

    #[Test]
    public function passesDestructuringAssignment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/Destructuring.php'],
            [],
        );
    }

    #[Test]
    public function suppressesChainedAssignmentWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MultipleVariableDeclarationsRule/SuppressedChained.php'],
            [],
        );
    }
}
