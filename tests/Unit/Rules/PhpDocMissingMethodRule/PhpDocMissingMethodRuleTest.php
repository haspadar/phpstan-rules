<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingMethodRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingMethodRule> */
final class PhpDocMissingMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocMissingMethodRule(['checkPublicOnly' => false, 'skipOverridden' => false]);
    }

    #[Test]
    public function passesWhenMethodHasPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/ClassWithPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenPublicMethodMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/ClassWithMissingPhpDoc.php'],
            [
                ['PHPDoc is missing for method greet().', 9],
            ],
        );
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
    public function reportsErrorWhenOverriddenMethodMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/ClassWithOverriddenMethod.php'],
            [
                ['PHPDoc is missing for method toString().', 9],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/SuppressedMethod.php'],
            [],
        );
    }
}
