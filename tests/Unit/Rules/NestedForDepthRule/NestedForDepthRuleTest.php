<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedForDepthRule;

use Haspadar\PHPStanRules\Rules\NestedForDepthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedForDepthRule> */
final class NestedForDepthRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedForDepthRule(1);
    }

    #[Test]
    public function passesWhenMethodHasNoNestedLoop(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/ShallowFor.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenNestingExactlyMatchesLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/OneLevelNested.php'],
            [],
        );
    }

    #[Test]
    public function reportsNestingThatExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/TwoLevelsNested.php'],
            [
                [
                    'Nested loop depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\TwoLevelsNested::flatten(). Maximum allowed is 1.',
                    14,
                ],
            ],
        );
    }

    #[Test]
    public function reportsEachLoopThatBreaksLimitInDeepCascade(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/ThreeLevelsNested.php'],
            [
                [
                    'Nested loop depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\ThreeLevelsNested::dive(). Maximum allowed is 1.',
                    14,
                ],
                [
                    'Nested loop depth is 3 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\ThreeLevelsNested::dive(). Maximum allowed is 1.',
                    15,
                ],
            ],
        );
    }

    #[Test]
    public function reportsMixedLoopTypesAsNested(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/MixedLoopTypes.php'],
            [
                [
                    'Nested loop depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\MixedLoopTypes::chew(). Maximum allowed is 1.',
                    15,
                ],
                [
                    'Nested loop depth is 3 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\MixedLoopTypes::chew(). Maximum allowed is 1.',
                    17,
                ],
            ],
        );
    }

    #[Test]
    public function passesIfBetweenLoopsBecauseIfIsNotLoop(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/IfBetweenLoops.php'],
            [],
        );
    }

    #[Test]
    public function passesMatchBetweenLoopsBecauseMatchIsNotLoop(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/MatchBetweenLoops.php'],
            [],
        );
    }

    #[Test]
    public function passesSwitchBetweenLoopsBecauseSwitchIsNotLoop(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/SwitchBetweenLoops.php'],
            [],
        );
    }

    #[Test]
    public function passesLoopInsideClosureBecauseClosureResetsDepth(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/LoopInsideClosure.php'],
            [],
        );
    }

    #[Test]
    public function passesAbstractMethodWithoutBody(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/AbstractMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesSiblingNestedLoopsBecauseDepthCounterResetsBetweenScopes(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/SiblingLoopsWithNested.php'],
            [],
        );
    }

    #[Test]
    public function reportsNestedLoopsAfterArrowFunctionRestoresScope(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/LoopAfterArrowFunction.php'],
            [
                [
                    'Nested loop depth is 2 in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule\LoopAfterArrowFunction::run(). Maximum allowed is 1.',
                    16,
                ],
            ],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedForDepthRule/SuppressedNested.php'],
            [],
        );
    }
}
