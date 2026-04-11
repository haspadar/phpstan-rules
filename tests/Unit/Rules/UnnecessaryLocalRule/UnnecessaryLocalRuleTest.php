<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\UnnecessaryLocalRule;

use Haspadar\PHPStanRules\Rules\UnnecessaryLocalRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<UnnecessaryLocalRule> */
final class UnnecessaryLocalRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new UnnecessaryLocalRule();
    }

    #[Test]
    public function passesWhenReturnIsDirectExpression(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/ValidDirectReturn.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenVariableIsAssignedAndImmediatelyReturned(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/UnnecessaryBeforeReturn.php'],
            [
                ['Variable $result is assigned and immediately returned. Return the expression directly.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenVariableIsAssignedAndImmediatelyThrown(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/UnnecessaryBeforeThrow.php'],
            [
                ['Variable $exception is assigned and immediately thrown. Throw the expression directly.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenVariableIsUsedElsewhere(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/UsedElsewhere.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenVariableHasVarPhpDoc(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/WithVarPhpDoc.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForUnnecessaryReturnInMultipleReturns(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/MultipleReturns.php'],
            [
                ['Variable $result is assigned and immediately returned. Return the expression directly.', 15],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/SuppressedUnnecessary.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenUnnecessaryVariableIsInsideClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/InsideClosure.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodReturnsByReference(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/ByReferenceMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenVariableVariableIsUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/VariableVariable.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsAbstract(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/UnnecessaryLocalRule/AbstractMethod.php'],
            [],
        );
    }
}
