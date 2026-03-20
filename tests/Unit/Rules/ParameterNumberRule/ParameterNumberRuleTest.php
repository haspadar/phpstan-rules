<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParameterNumberRule;

use Haspadar\PHPStanRules\Rules\ParameterNumberRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ParameterNumberRule> */
final class ParameterNumberRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ParameterNumberRule(3);
    }

    #[Test]
    public function passesWhenMethodFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/ShortMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/LongMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNumberRule\LongMethod::create() has 4 parameters. Maximum allowed is 3.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/ExactMethod.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/SuppressedLongMethod.php'],
            [],
        );
    }

    #[Test]
    public function skipsOverriddenMethodsByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/ClassWithOverriddenMethod.php'],
            [],
        );
    }
}
