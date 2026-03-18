<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MethodLengthRule;

use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MethodLengthRule> */
final class MethodLengthRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new MethodLengthRule();
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/ExactDefaultMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/MethodLengthRule/LongDefaultMethod.php'],
            [
                ['Method run() is 101 lines long. Maximum allowed is 100.', 9],
            ],
        );
    }
}
