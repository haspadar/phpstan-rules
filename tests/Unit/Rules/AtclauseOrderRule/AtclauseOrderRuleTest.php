<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AtclauseOrderRule;

use Haspadar\PHPStanRules\Rules\AtclauseOrderRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<AtclauseOrderRule> */
final class AtclauseOrderRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AtclauseOrderRule();
    }

    #[Test]
    public function passesWhenTagsAreInCorrectOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithCorrectOrder.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenReturnComesAfterThrows(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithWrongOrder.php'],
            [
                ['PHPDoc tag @return must come before @throws in save().', 18],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodHasNoPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithNoPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenDocHasOnlyOneRelevantTag(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithTagsOnly.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/SuppressedMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsInInterface(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/InterfaceWithWrongOrder.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsInTrait(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/TraitWithWrongOrder.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenExactlyTwoTagsAreInWrongOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithExactlyTwoTagsWrongOrder.php'],
            [
                ['PHPDoc tag @return must come before @throws in save().', 16],
            ],
        );
    }

    #[Test]
    public function passesWhenAllTagsAreIrrelevantToOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithIrrelevantTagsOnly.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenOnlyOneRelevantTagPresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithParamOnly.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenDuplicateParamTagsArePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithDuplicateParam.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenIrrelevantTagAppearsBeforeViolation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithIrrelevantTagBetweenRelevant.php'],
            [
                ['PHPDoc tag @return must come before @throws in save().', 18],
            ],
        );
    }

    #[Test]
    public function reportsAllViolationsWhenMultipleTagsAreOutOfOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AtclauseOrderRule/ClassWithMultipleViolations.php'],
            [
                ['PHPDoc tag @return must come before @throws in save().', 18],
                ['PHPDoc tag @param must come before @throws in save().', 18],
            ],
        );
    }
}
