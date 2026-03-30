<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingMethodRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingMethodRule> */
final class PhpDocMissingMethodRuleOptionTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocMissingMethodRule(['checkPublicOnly' => false, 'skipOverridden' => true]);
    }

    #[Test]
    public function reportsErrorWhenProtectedMethodMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/ClassWithProtectedMethod.php'],
            [
                ['PHPDoc is missing for method inner().', 9],
            ],
        );
    }

    #[Test]
    public function passesWhenOverriddenMethodMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/ClassWithOverriddenMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenAliasedOverrideAttributePresentButNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/ClassWithAliasedOverrideAttribute.php'],
            [],
        );
    }
}
