<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\VariableNameRule;

use Haspadar\PHPStanRules\Rules\VariableNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<VariableNameRule> */
final class VariableNameRuleAllowedNamesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new VariableNameRule('^[a-z][a-zA-Z]{2,9}$', ['allowedNames' => ['db']]);
    }

    #[Test]
    public function passesWhenNameIsInAllowedNames(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/AllowedName.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsNotInAllowedNames(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ShortName.php'],
            [
                ['Variable $x does not match pattern /^[a-z][a-zA-Z]{2,9}$/.', 11],
            ],
        );
    }
}
