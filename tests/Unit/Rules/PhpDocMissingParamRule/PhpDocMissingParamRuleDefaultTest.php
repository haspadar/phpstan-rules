<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingParamRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingParamRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingParamRule> */
final class PhpDocMissingParamRuleDefaultTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new PhpDocMissingParamRule();
    }

    #[Test]
    public function reportsMissingParamInPublicMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithMissingParamTag.php'],
            [
                ['PHPDoc for greet() is missing @param for parameter $name.', 12],
            ],
            'Default options must still catch missing @param on public methods',
        );
    }

    #[Test]
    public function passesWhenPrivateMethodMissesParamTag(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithPrivateMethod.php'],
            [],
            'checkPublicOnly=true must skip private methods regardless of missing @param tags',
        );
    }

    #[Test]
    public function passesWhenOverriddenMethodMissesParamTag(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingParamRule/ClassWithOverriddenMethod.php'],
            [],
            'skipOverridden=true must skip #[Override] methods regardless of missing @param tags',
        );
    }
}
