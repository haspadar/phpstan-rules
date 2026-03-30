<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingPropertyRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingPropertyRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingPropertyRule> */
final class PhpDocMissingPropertyRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocMissingPropertyRule();
    }

    #[Test]
    public function passesWhenPublicPropertyHasPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenPublicPropertyMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithMissingPhpDoc.php'],
            [
                ['PHPDoc is missing for property $name.', 9],
            ],
        );
    }

    #[Test]
    public function passesWhenPrivatePropertyMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithPrivateProperty.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenProtectedPropertyMissingPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithProtectedProperty.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenPromotedPropertyUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/ClassWithPromotedProperty.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingPropertyRule/SuppressedProperty.php'],
            [],
        );
    }
}
