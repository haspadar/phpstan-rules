<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParameterNumberRule;

use Haspadar\PHPStanRules\Rules\ParameterNumberRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ParameterNumberRule> */
final class ParameterNumberRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ParameterNumberRule();
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/ExactDefaultMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/LongDefaultMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNumberRule\LongDefaultMethod::create() has 4 parameters. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }
}
