<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitLongTypeAliasRule;

use Haspadar\PHPStanRules\Rules\ProhibitLongTypeAliasRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitLongTypeAliasRule> */
final class ProhibitLongTypeAliasRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new ProhibitLongTypeAliasRule();
    }

    #[Test]
    public function reportsErrorWhenIntegerUsedInParam(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithLongTypeInParam.php'],
            [
                ['PHPDoc contains long type alias "integer", use "int" instead.', 15],
            ],
            '"integer" in @param must produce an error',
        );
    }

    #[Test]
    public function reportsErrorWhenBooleanUsedInReturn(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithLongTypeInReturn.php'],
            [
                ['PHPDoc contains long type alias "boolean", use "bool" instead.', 14],
            ],
            '"boolean" in @return must produce an error',
        );
    }

    #[Test]
    public function reportsErrorWhenDoubleAndRealUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithDoubleAndReal.php'],
            [
                ['PHPDoc contains long type alias "double", use "float" instead.', 16],
                ['PHPDoc contains long type alias "real", use "float" instead.', 16],
            ],
            '"double" and "real" in @param must each produce an error',
        );
    }

    #[Test]
    public function reportsErrorWhenAliasInsideUnionType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithUnionLongType.php'],
            [
                ['PHPDoc contains long type alias "integer", use "int" instead.', 15],
            ],
            '"integer" nested in a union type must produce an error',
        );
    }

    #[Test]
    public function reportsErrorWhenIntegerUsedInThrows(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithLongTypeInThrows.php'],
            [
                ['PHPDoc contains long type alias "integer", use "int" instead.', 17],
            ],
            '"integer" in @throws must produce an error',
        );
    }

    #[Test]
    public function reportsErrorWhenIntegerUsedInVar(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithLongTypeInVar.php'],
            [
                ['PHPDoc contains long type alias "integer", use "int" instead.', 10],
            ],
            '"integer" in @var on a property must produce an error',
        );
    }

    #[Test]
    public function passesWhenOnlyShortTypesUsed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithShortTypes.php'],
            [],
            'Short type forms must not produce any errors',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/SuppressedLongTypeAlias.php'],
            [],
            '@phpstan-ignore haspadar.prohibitLongTypeAlias must silence the error',
        );
    }

    #[Test]
    public function passesWhenMethodHasNoDocComment(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithNoDocComment.php'],
            [],
            'A method without a PHPDoc block must not produce any errors',
        );
    }

    #[Test]
    public function reportsErrorWhenMultipleAliasesInSameMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitLongTypeAliasRule/ClassWithMultipleLongTypeParams.php'],
            [
                ['PHPDoc contains long type alias "integer", use "int" instead.', 16],
                ['PHPDoc contains long type alias "integer", use "int" instead.', 16],
            ],
            'Two @param tags with "integer" must each produce a separate error',
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
            '"INTEGER" in uppercase must be reported with the correct short form',
        );
    }
}
