<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocEmptyClassRule;

use Haspadar\PHPStanRules\Rules\PhpDocEmptyClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocEmptyClassRule> */
final class PhpDocEmptyClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocEmptyClassRule();
    }

    #[Test]
    public function passesWhenClassHasSummary(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyClassRule/ClassWithSummary.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenPhpDocIsEmpty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyClassRule/ClassWithEmptyPhpDoc.php'],
            [
                ['PHPDoc for ClassWithEmptyPhpDoc must contain a summary line.', 8],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenPhpDocHasTagsOnly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyClassRule/ClassWithTagsOnly.php'],
            [
                ['PHPDoc for ClassWithTagsOnly must contain a summary line.', 8],
            ],
        );
    }

    #[Test]
    public function passesWhenClassHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyClassRule/ClassWithNoPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyClassRule/SuppressedClass.php'],
            [],
        );
    }
}
