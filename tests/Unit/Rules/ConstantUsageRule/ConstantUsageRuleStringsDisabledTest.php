<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ConstantUsageRule;

use Haspadar\PHPStanRules\Rules\ConstantUsageRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ConstantUsageRule> */
final class ConstantUsageRuleStringsDisabledTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ConstantUsageRule();
    }

    #[Test]
    public function passesWhenStringCheckingIsDisabledByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ConstantUsageRule/ClassWithStringsDisabled.php'],
            [],
        );
    }
}
