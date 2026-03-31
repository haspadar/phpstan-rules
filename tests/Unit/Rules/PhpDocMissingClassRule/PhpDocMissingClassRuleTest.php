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
}
