<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoPhpDocForOverriddenRule;

use Haspadar\PHPStanRules\Rules\NoPhpDocForOverriddenRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoPhpDocForOverriddenRule> */
final class NoPhpDocForOverriddenRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoPhpDocForOverriddenRule();
    }

    #[Test]
    public function reportsErrorWhenOverriddenMethodHasPhpDoc(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/NoPhpDocForOverriddenRule/OverriddenMethodWithPhpDoc.php',
                __DIR__ . '/../../../Fixtures/Rules/NoPhpDocForOverriddenRule/ChildWithPhpDoc.php',
            ],
            [
                ['Overridden method doSomething() must not have a PHPDoc comment.', 13],
            ],
            'Overridden method with PHPDoc must be reported',
        );
    }

    #[Test]
    public function passesWhenOverriddenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/NoPhpDocForOverriddenRule/OverriddenMethodWithPhpDoc.php',
                __DIR__ . '/../../../Fixtures/Rules/NoPhpDocForOverriddenRule/ChildWithoutPhpDoc.php',
            ],
            [],
            'Overridden method without PHPDoc should pass',
        );
    }

    #[Test]
    public function passesWhenNonOverriddenMethodHasPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoPhpDocForOverriddenRule/NonOverriddenMethodWithPhpDoc.php'],
            [],
            'Non-overridden method with PHPDoc should pass',
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/NoPhpDocForOverriddenRule/OverriddenMethodWithPhpDoc.php',
                __DIR__ . '/../../../Fixtures/Rules/NoPhpDocForOverriddenRule/SuppressedOverriddenMethod.php',
            ],
            [],
            'Suppressed error should pass',
        );
    }
}
