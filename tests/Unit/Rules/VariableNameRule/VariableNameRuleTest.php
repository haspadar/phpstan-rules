<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\VariableNameRule;

use Haspadar\PHPStanRules\Rules\VariableNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<VariableNameRule> */
final class VariableNameRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new VariableNameRule('^[a-z][a-zA-Z]{2,9}$', ['allowedNames' => ['id']]);
    }

    #[Test]
    public function passesWhenVariableNamesAreValid(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ValidNamesCustom.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsTooShort(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ShortName.php'],
            [
                ['Variable $x does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameContainsDigit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/NameWithDigit.php'],
            [
                ['Variable $item2 does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameContainsUnderscore(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/NameWithUnderscore.php'],
            [
                ['Variable $my_var does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsTooLong(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/TooLongName.php'],
            [
                ['Variable $extremelyLongVariableName does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/SuppressedShortName.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForInvalidForeachVariable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ForeachVariable.php'],
            [
                ['Variable $k does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 14],
                ['Variable $v does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 14],
            ],
        );
    }

    #[Test]
    public function reportsErrorForInvalidDestructuringVariable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/DestructuringVariable.php'],
            [
                ['Variable $x does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorForInvalidStaticVariable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/StaticVariable.php'],
            [
                ['Variable $x does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }

    #[Test]
    public function skipsVariablesInsideClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ClosureVariable.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenForVariableNotInAllowedNames(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ForVariable.php'],
            [
                ['Variable $i does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }

    #[Test]
    public function reportsErrorForDestructuredForeachVariable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ForeachDestructuring.php'],
            [
                ['Variable $x does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 14],
            ],
        );
    }
}
