<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocMissingClassRule;

use Haspadar\PHPStanRules\Rules\PhpDocMissingClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocMissingClassRule> */
final class PhpDocMissingClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocMissingClassRule();
    }

    #[Test]
    public function passesWhenClassHasPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/ClassWithPhpDoc.php'],
            [],
            'Class with PHPDoc should pass',
        );
    }

    #[Test]
    public function reportsErrorWhenClassHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/ClassWithoutPhpDoc.php'],
            [
                ['PHPDoc is missing for class ClassWithoutPhpDoc.', 7],
            ],
            'Class without PHPDoc must be reported',
        );
    }

    #[Test]
    public function passesWhenClassIsAnonymous(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/AnonymousClass.php'],
            [],
            'Anonymous class should be skipped',
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/SuppressedClass.php'],
            [],
            'Suppressed error should pass',
        );
    }

    #[Test]
    public function reportsErrorWhenAbstractClassHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/AbstractClassWithoutPhpDoc.php'],
            [
                ['PHPDoc is missing for class AbstractClassWithoutPhpDoc.', 7],
            ],
            'Abstract class without PHPDoc must be reported',
        );
    }

    #[Test]
    public function reportsErrorWhenInterfaceHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/InterfaceWithoutPhpDoc.php'],
            [
                ['PHPDoc is missing for interface InterfaceWithoutPhpDoc.', 7],
            ],
            'Interface without PHPDoc must be reported',
        );
    }

    #[Test]
    public function passesWhenInterfaceHasPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/InterfaceWithPhpDoc.php'],
            [],
            'Interface with PHPDoc should pass',
        );
    }

    #[Test]
    public function reportsErrorWhenEnumHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/EnumWithoutPhpDoc.php'],
            [
                ['PHPDoc is missing for enum EnumWithoutPhpDoc.', 7],
            ],
            'Enum without PHPDoc must be reported',
        );
    }

    #[Test]
    public function passesWhenEnumHasPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocMissingClassRule/EnumWithPhpDoc.php'],
            [],
            'Enum with PHPDoc should pass',
        );
    }
}
