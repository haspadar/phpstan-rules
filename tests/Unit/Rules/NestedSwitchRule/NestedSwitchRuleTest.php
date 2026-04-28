<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NestedSwitchRule;

use Haspadar\PHPStanRules\Rules\NestedSwitchRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NestedSwitchRule> */
final class NestedSwitchRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NestedSwitchRule();
    }

    #[Test]
    public function reportsNestedSwitchStatement(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedSwitchRule/NestedSwitch.php'],
            [
                [
                    'Nested switch statements are forbidden — extract the inner switch into a separate method.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenSwitchIsNotNested(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedSwitchRule/SingleSwitch.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenSwitchIsInsideClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedSwitchRule/SwitchInClosure.php'],
            [],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NestedSwitchRule/SuppressedClass.php'],
            [],
        );
    }
}
