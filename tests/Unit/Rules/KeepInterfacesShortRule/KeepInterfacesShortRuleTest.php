<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\KeepInterfacesShortRule;

use Haspadar\PHPStanRules\Rules\KeepInterfacesShortRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<KeepInterfacesShortRule> */
final class KeepInterfacesShortRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new KeepInterfacesShortRule(3);
    }

    #[Test]
    public function passesWhenInterfaceFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/KeepInterfacesShortRule/ShortInterface.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenInterfaceExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/KeepInterfacesShortRule/LongInterface.php'],
            [
                ['Interface LongInterface has 4 methods. Maximum allowed is 3.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenInterfaceIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/KeepInterfacesShortRule/ExactInterface.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/KeepInterfacesShortRule/SuppressedInterface.php'],
            [],
        );
    }
}
