<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InnerAssignmentRule;

use Haspadar\PHPStanRules\Rules\InnerAssignmentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<InnerAssignmentRule> */
final class InnerAssignmentRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new InnerAssignmentRule();
    }

    #[Test]
    public function passesWhenAssignmentsAreStandaloneStatements(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithNoInnerAssignment.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenAssignInIfCondition(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithAssignInIf.php'],
            [
                ['Inner assignment found. Assignments must not be used as subexpressions.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenAssignInReturn(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithAssignInReturn.php'],
            [
                ['Inner assignment found. Assignments must not be used as subexpressions.', 13],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenChainedAssign(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithChainedAssign.php'],
            [
                ['Inner assignment found. Assignments must not be used as subexpressions.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenAssignInFunctionArg(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithAssignInFunctionArg.php'],
            [
                ['Inner assignment found. Assignments must not be used as subexpressions.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenAssignInForBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/ClassWithAssignInForBody.php'],
            [
                ['Inner assignment found. Assignments must not be used as subexpressions.', 15],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InnerAssignmentRule/SuppressedClass.php'],
            [],
        );
    }
}
