<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\VariableNameRule;

use Haspadar\PHPStanRules\Rules\VariableNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<VariableNameRule> */
final class VariableNameRuleDefaultPatternTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new VariableNameRule();
    }

    #[Test]
    public function passesWhenVariableNamesMatchDefaultPattern(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ValidNames.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsTooShortWithDefaultPattern(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ShortName.php'],
            [
                ['Variable $x does not match pattern /^[a-z][a-zA-Z]{2,19}$/.', 11],
            ],
        );
    }

    #[Test]
    public function passesWhenForVariableIsInDefaultAllowedNames(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/VariableNameRule/ForVariable.php'],
            [],
        );
    }
}
