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
        return new ProhibitLongTypeAliasRule(['Integer']);
    }

    #[Test]
    public function passesWhenAliasIsAllowed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithAllowedAlias.php'],
            [],
            '"Integer" listed in allowedAliases must not produce an error',
        );
    }

    #[Test]
    public function stillReportsOtherAliasesWhenOneIsAllowed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithLongTypeInReturn.php'],
            [
                ['PHPDoc contains long type alias "boolean", use "bool" instead.', 14],
            ],
            'Allowing "Integer" must not suppress errors for "boolean"',
        );
    }
}
