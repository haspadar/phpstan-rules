<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\SwitchDefaultRule;

use Haspadar\PHPStanRules\Rules\SwitchDefaultRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<SwitchDefaultRule> */
final class SwitchDefaultRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new SwitchDefaultRule();
    }

    #[Test]
    public function reportsWhenSwitchHasNoDefaultCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SwitchDefaultRule/NoDefault.php'],
            [
                [
                    'Switch statement must have a default case.',
                    11,
                ],
            ],
        );
    }

    #[Test]
    public function reportsWhenDefaultIsNotLastCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SwitchDefaultRule/DefaultNotLast.php'],
            [
                [
                    'Default case must be the last case in a switch statement.',
                    15,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenSwitchHasDefaultAsLastCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SwitchDefaultRule/WithDefault.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSwitchIsEmpty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SwitchDefaultRule/EmptySwitch.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSwitchHasOnlyDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SwitchDefaultRule/DefaultOnly.php'],
            [],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/SwitchDefaultRule/SuppressedClass.php'],
            [],
        );
    }
}
