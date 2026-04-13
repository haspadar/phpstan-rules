<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\KeepInterfacesShortRule;

use Haspadar\PHPStanRules\Rules\KeepInterfacesShortRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<KeepInterfacesShortRule> */
final class KeepInterfacesShortRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new KeepInterfacesShortRule();
    }

    #[Test]
    public function reportsErrorWhenInterfaceExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/KeepInterfacesShortRule/LongDefaultInterface.php'],
            [
                ['Interface LongDefaultInterface has 11 methods. Maximum allowed is 10.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenInterfaceIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/KeepInterfacesShortRule/ExactDefaultInterface.php'],
            [],
        );
    }
}
