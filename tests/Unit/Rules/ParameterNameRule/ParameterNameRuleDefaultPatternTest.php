<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParameterNameRule;

use Haspadar\PHPStanRules\Rules\ParameterNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ParameterNameRule> */
final class ParameterNameRuleDefaultPatternTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ParameterNameRule();
    }

    #[Test]
    public function passesWhenParameterNamesMatchDefaultPattern(): void
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
                ['Parameter $fn does not match pattern /^(id|[a-z]{3,})$/.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsCamelCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNameRule/CamelCaseName.php'],
            [
                ['Parameter $userName does not match pattern /^(id|[a-z]{3,})$/.', 9],
            ],
        );
    }
}
