<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoNullAssignmentRule;

use Haspadar\PHPStanRules\Rules\NoNullAssignmentRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoNullAssignmentRule> */
final class NoNullAssignmentRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new NoNullAssignmentRule();
    }

    #[Test]
    public function reportsNullAssignmentToVariable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithNullAssignmentToVariable.php'],
            [
                ['Assignment of null to $value is prohibited. Model absence explicitly (Null Object, Optional).', 11],
            ],
            'A null literal assigned to a local variable must be reported',
        );
    }

    #[Test]
    public function reportsNullAssignmentToProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithNullAssignmentToProperty.php'],
            [
                ['Assignment of null to $this->cache is prohibited. Model absence explicitly (Null Object, Optional).', 13],
            ],
            'A null literal assigned to a property must be reported with the property name',
        );
    }

    #[Test]
    public function reportsNullAssignmentToStaticProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithNullAssignmentToStaticProperty.php'],
            [
                ['Assignment of null to self::$cache is prohibited. Model absence explicitly (Null Object, Optional).', 13],
            ],
            'A null literal assigned to a static property must be reported as Class::$name',
        );
    }

    #[Test]
    public function reportsNullAssignmentToArrayElement(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithNullAssignmentToArrayElement.php'],
            [
                ['Assignment of null to array element is prohibited. Model absence explicitly (Null Object, Optional).', 15],
            ],
            'A null literal assigned to an array element must be reported',
        );
    }

    #[Test]
    public function reportsEveryNullAssignmentInTheSameMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithMultipleNullAssignments.php'],
            [
                ['Assignment of null to $first is prohibited. Model absence explicitly (Null Object, Optional).', 14],
                ['Assignment of null to $this->data is prohibited. Model absence explicitly (Null Object, Optional).', 15],
                ['Assignment of null to array element is prohibited. Model absence explicitly (Null Object, Optional).', 16],
            ],
            'Each null assignment must produce its own error with the target described',
        );
    }

    #[Test]
    public function passesWhenAssignedValueIsNotNull(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithoutNullAssignment.php'],
            [],
            'Assignments of non-null values must never produce an error',
        );
    }

    #[Test]
    public function passesWhenNullAppearsOnlyInNullableDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithNullableParameter.php'],
            [],
            'Nullable parameter defaults are declarations, not runtime assignments, and must not be flagged',
        );
    }

    #[Test]
    public function passesWhenNullAppearsOnlyInCoalesceOperand(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/ClassWithCoalesceAssignment.php'],
            [],
            'Coalescing expressions on the right-hand side without a null literal must not be flagged',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullAssignmentRule/SuppressedNullAssignment.php'],
            [],
            'A @phpstan-ignore haspadar.noNullAssignment comment must silence the error',
        );
    }
}
