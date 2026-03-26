<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoParameterReassignmentRule;

use Haspadar\PHPStanRules\Rules\NoParameterReassignmentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoParameterReassignmentRule> */
final class NoParameterReassignmentRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoParameterReassignmentRule();
    }

    #[Test]
    public function passesWhenParameterIsNotReassigned(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithCleanMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenOnlyPropertyIsAssigned(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithPropertyAssignment.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenLocalVariableIsAssigned(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithLocalVariableAssignment.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForParameterReassignment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithParameterReassignment.php'],
            [
                [
                    'Parameter $name must not be reassigned in method greet() of Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule\ClassWithParameterReassignment.',
                    11,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForConstructorParameterReassignment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithConstructorParameterReassignment.php'],
            [
                [
                    'Parameter $name must not be reassigned in method __construct() of Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule\ClassWithConstructorParameterReassignment.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function reportsEachViolationIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithMultipleParameterReassignments.php'],
            [
                [
                    'Parameter $first must not be reassigned in method format() of Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule\ClassWithMultipleParameterReassignments.',
                    11,
                ],
                [
                    'Parameter $last must not be reassigned in method format() of Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule\ClassWithMultipleParameterReassignments.',
                    12,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForReassignmentInsideIfBlock(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithReassignmentInIf.php'],
            [
                [
                    'Parameter $name must not be reassigned in method process() of Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule\ClassWithReassignmentInIf.',
                    12,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenReassignmentIsInsideClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithReassignmentInClosure.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenConstructorUsesPropertyPromotion(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/ClassWithPromotedPropertyParameter.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoParameterReassignmentRule/SuppressedClassWithParameterReassignment.php'],
            [],
        );
    }
}
