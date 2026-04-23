<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoNullablePropertyRule;

use Haspadar\PHPStanRules\Rules\NoNullablePropertyRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoNullablePropertyRule> */
final class NoNullablePropertyRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new NoNullablePropertyRule();
    }

    #[Test]
    public function reportsNullablePropertyWithShortSyntax(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithNullablePropertyShortSyntax.php'],
            [
                ['Property $name in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithNullablePropertyShortSyntax must not be nullable.', 9],
            ],
            'A property declared with the ?Type shorthand must be reported',
        );
    }

    #[Test]
    public function reportsNullablePropertyWithUnionSyntax(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithNullablePropertyUnionSyntax.php'],
            [
                ['Property $name in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithNullablePropertyUnionSyntax must not be nullable.', 9],
            ],
            'A property declared as Type|null union must be reported',
        );
    }

    #[Test]
    public function reportsNullablePropertyWithReversedUnion(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithNullablePropertyUnionReversed.php'],
            [
                ['Property $name in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithNullablePropertyUnionReversed must not be nullable.', 9],
            ],
            'A property declared as null|Type union must be reported regardless of null position',
        );
    }

    #[Test]
    public function reportsNullableReadonlyProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithNullableReadonlyProperty.php'],
            [
                ['Property $age in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithNullableReadonlyProperty must not be nullable.', 9],
            ],
            'Readonly modifier must not exempt a property from the nullable-type check',
        );
    }

    #[Test]
    public function reportsNullableStaticProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithNullableStaticProperty.php'],
            [
                ['Property $cache in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithNullableStaticProperty must not be nullable.', 9],
            ],
            'Static modifier must not exempt a property from the nullable-type check',
        );
    }

    #[Test]
    public function reportsEveryPropertyInGroupedDeclaration(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithGroupedNullableProperties.php'],
            [
                ['Property $first in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithGroupedNullableProperties must not be nullable.', 10],
                ['Property $second in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithGroupedNullableProperties must not be nullable.', 11],
            ],
            'Each property in a grouped declaration must be reported on its own line',
        );
    }

    #[Test]
    public function reportsNullableWhenNullAppearsInMiddleOfUnion(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithThreePartUnionContainingNull.php'],
            [
                ['Property $value in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithThreePartUnionContainingNull must not be nullable.', 9],
            ],
            'A null at any position in a multi-part union must trigger the rule',
        );
    }

    #[Test]
    public function passesWhenPropertyHasIntersectionType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithIntersectionTypeProperty.php'],
            [],
            'An intersection type A&B never contains null and must not be reported',
        );
    }

    #[Test]
    public function reportsEveryNullablePropertyInTheSameClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithMultipleNullableProperties.php'],
            [
                ['Property $name in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithMultipleNullableProperties must not be nullable.', 9],
                ['Property $age in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithMultipleNullableProperties must not be nullable.', 11],
                ['Property $score in class Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule\ClassWithMultipleNullableProperties must not be nullable.', 13],
            ],
            'Each nullable property in the class must produce its own error',
        );
    }

    #[Test]
    public function passesWhenPropertyIsNotNullable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithoutNullableProperty.php'],
            [],
            'Non-nullable properties must never produce an error',
        );
    }

    #[Test]
    public function passesWhenPropertyIsPromoted(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/ClassWithPromotedNullableProperty.php'],
            [],
            'Promoted constructor properties are out of scope and must be handled by NeverAcceptNullArgumentsRule',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullablePropertyRule/SuppressedNullableProperty.php'],
            [],
            'A @phpstan-ignore haspadar.noNullableProperty comment must silence the error',
        );
    }
}
