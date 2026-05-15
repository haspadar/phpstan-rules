<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitLongTypeAliasRule;

use Haspadar\PHPStanRules\Rules\ProhibitLongTypeAliasRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitLongTypeAliasRule> */
final class ProhibitLongTypeAliasRuleAllowedAliasTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new ProhibitLongTypeAliasRule();
    }

    #[Test]
    public function passesWhenPascalCaseClassUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithAllowedAlias.php'],
            [],
            '"Integer" in PascalCase must be treated as a user-defined class and allowed',
        );
    }

    #[Test]
    public function passesWhenPascalCasePseudoTypeUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithAllowedPseudoTypes.php'],
            [],
            '"Scalar", "Mixed", "Resource" in PascalCase must be treated as user-defined classes and allowed',
        );
    }

    #[Test]
    public function reportsErrorWhenUppercaseAliasUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithUppercaseAlias.php'],
            [
                ['PHPDoc contains long type alias "INTEGER", use "int" instead.', 15],
            ],
            '"INTEGER" must be reported as a long alias despite uppercase spelling',
        );
    }
}
