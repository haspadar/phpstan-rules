<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParameterNameRule;

use Haspadar\PHPStanRules\Rules\ParameterNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ParameterNameRule> */
final class ParameterNameRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ParameterNameRule('^[a-z]{3,10}$');
    }

    #[Test]
    public function passesWhenParameterNamesAreValid(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/ValidNames.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsTooShort(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/ShortName.php'],
            [
                ['Parameter $fn does not match pattern /^[a-z]{3,10}$/.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsCamelCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/CamelCaseName.php'],
            [
                ['Parameter $userName does not match pattern /^[a-z]{3,10}$/.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameContainsDigit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/NameWithDigit.php'],
            [
                ['Parameter $item2 does not match pattern /^[a-z]{3,10}$/.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameContainsUnderscore(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/NameWithUnderscore.php'],
            [
                ['Parameter $user_name does not match pattern /^[a-z]{3,10}$/.', 9],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/SuppressedShortName.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForPromotedProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/PromotedProperty.php'],
            [
                ['Parameter $nm does not match pattern /^[a-z]{3,10}$/.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorsForMultipleInvalidParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/MultipleParameters.php'],
            [
                ['Parameter $x does not match pattern /^[a-z]{3,10}$/.', 9],
                ['Parameter $ok does not match pattern /^[a-z]{3,10}$/.', 9],
            ],
        );
    }

    #[Test]
    public function skipsClosureParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/ClosureParameter.php'],
            [],
        );
    }

    #[Test]
    public function skipsArrowFunctionParameters(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/ArrowFunctionParameter.php'],
            [],
        );
    }
}
