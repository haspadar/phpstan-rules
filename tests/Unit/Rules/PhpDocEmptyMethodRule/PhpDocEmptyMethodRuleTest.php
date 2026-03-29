<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocEmptyMethodRule;

use Haspadar\PHPStanRules\Rules\PhpDocEmptyMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocEmptyMethodRule> */
final class PhpDocEmptyMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new PhpDocEmptyMethodRule();
    }

    #[Test]
    public function passesWhenMethodHasSummary(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyMethodRule/ClassWithMethodSummary.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodPhpDocIsEmpty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyMethodRule/ClassWithEmptyMethodPhpDoc.php'],
            [
                ['PHPDoc for save() must contain a summary line.', 10],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodPhpDocHasTagsOnly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyMethodRule/ClassWithMethodTagsOnly.php'],
            [
                ['PHPDoc for save() must contain a summary line.', 12],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyMethodRule/ClassWithNoMethodPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyMethodRule/SuppressedMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsInInterface(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyMethodRule/InterfaceWithEmptyMethodPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsInTrait(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocEmptyMethodRule/TraitWithEmptyMethodPhpDoc.php'],
            [],
        );
    }
}
