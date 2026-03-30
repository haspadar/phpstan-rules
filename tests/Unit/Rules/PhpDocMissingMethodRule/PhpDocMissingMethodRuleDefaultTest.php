<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingMethodRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingMethodRule> */
final class PhpDocMissingMethodRuleDefaultTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocMissingMethodRule();
    }

    #[Test]
    public function passesWhenPublicMethodHasPhpDoc(): void
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
    public function passesWhenPrivateMethodMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingMethodRule/ClassWithPrivateMethod.php'],
            [],
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
}
