<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CatchParameterNameRule;

use Haspadar\PHPStanRules\Rules\CatchParameterNameRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CatchParameterNameRule> */
final class CatchParameterNameRuleDefaultPatternTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CatchParameterNameRule();
    }

    #[Test]
    public function passesWhenCatchParameterNamesMatchDefaultPattern(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/ValidNames.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsTooShort(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/ShortName.php'],
            [
                ['Catch parameter $x does not match pattern /^(e|ex|[a-z]{3,12})$/.', 13],
            ],
        );
    }

    #[Test]
    public function reportsErrorWhenNameIsCamelCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CatchParameterNameRule/CamelCaseName.php'],
            [
                ['Catch parameter $myException does not match pattern /^(e|ex|[a-z]{3,12})$/.', 13],
            ],
        );
    }
}
