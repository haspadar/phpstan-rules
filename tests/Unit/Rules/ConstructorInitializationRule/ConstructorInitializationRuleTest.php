<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ConstructorInitializationRule;

use Haspadar\PHPStanRules\Rules\ConstructorInitializationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ConstructorInitializationRule> */
final class ConstructorInitializationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ConstructorInitializationRule();
    }

    #[Test]
    public function passesWhenConstructorOnlyAssignsProperties(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithCleanConstructor.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenConstructorCallsParent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithParentCall.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenConstructorUsesPropertyPromotion(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithConstructorPromotion.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForFunctionCallInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithFunctionCallInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithFunctionCallInConstructor must only initialize properties. Found: Stmt_Expression.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForMethodCallInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithMethodCallInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithMethodCallInConstructor must only initialize properties. Found: Stmt_Expression.',
                    14,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForIfStatementInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithIfInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithIfInConstructor must only initialize properties. Found: Stmt_If.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenConstructorAssignsArray(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithArrayInConstructor.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenConstructorAssignsConstant(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithConstInConstructor.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForSelfCallInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithSelfCallInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithSelfCallInConstructor must only initialize properties. Found: Stmt_Expression.',
                    14,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForNonConstructorParentCallInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithParentMethodCallInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithParentMethodCallInConstructor must only initialize properties. Found: Stmt_Expression.',
                    20,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenConstructorAssignsLiterals(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithLiteralsInConstructor.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenConstructorAssignsNewObject(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithNewInConstructor.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForOtherObjectPropertyAssignInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithOtherObjectAssignInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithOtherObjectAssignInConstructor must only initialize properties. Found: Stmt_Expression.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function reportsErrorForLocalVariableAssignInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithLocalVariableAssignInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithLocalVariableAssignInConstructor must only initialize properties. Found: Stmt_Expression.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function reportsEachViolationIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/ClassWithMultipleViolationsInConstructor.php'],
            [
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithMultipleViolationsInConstructor must only initialize properties. Found: Stmt_Expression.',
                    15,
                ],
                [
                    'Constructor of Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule\ClassWithMultipleViolationsInConstructor must only initialize properties. Found: Stmt_Expression.',
                    16,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstructorInitializationRule/SuppressedClassWithFunctionCallInConstructor.php'],
            [],
        );
    }
}
