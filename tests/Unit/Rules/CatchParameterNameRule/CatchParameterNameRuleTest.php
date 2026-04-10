<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CatchParameterNameRule;

use Haspadar\PHPStanRules\Rules\CatchParameterNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CatchParameterNameRule> */
final class CatchParameterNameRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CatchParameterNameRule('^[a-z]{3,8}$');
    }

    #[Test]
    public function passesWhenCatchParameterNamesAreValid(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/ValidNames.php'],
            [
                ['Catch parameter $e does not match pattern /^[a-z]{3,8}$/.', 13],
                ['Catch parameter $ex does not match pattern /^[a-z]{3,8}$/.', 15],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsTooShort(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/ShortName.php'],
            [
                ['Catch parameter $x does not match pattern /^[a-z]{3,8}$/.', 13],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsCamelCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/CamelCaseName.php'],
            [
                ['Catch parameter $myException does not match pattern /^[a-z]{3,8}$/.', 13],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameContainsDigit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/NameWithDigit.php'],
            [
                ['Catch parameter $ex1 does not match pattern /^[a-z]{3,8}$/.', 13],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsTooLong(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/TooLongName.php'],
            [
                ['Catch parameter $veryverylongname does not match pattern /^[a-z]{3,8}$/.', 13],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/SuppressedShortName.php'],
            [],
        );
    }

    #[Test]
    public function skipsUnnamedCatch(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/UnnamedCatch.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForMultipleCatchTypes(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/MultipleCatchTypes.php'],
            [
                ['Catch parameter $x does not match pattern /^[a-z]{3,8}$/.', 13],
            ],
        );
    }
}
