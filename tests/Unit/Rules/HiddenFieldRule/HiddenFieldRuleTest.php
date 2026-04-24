<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\HiddenFieldRule;

use Haspadar\PHPStanRules\Rules\HiddenFieldRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<HiddenFieldRule> */
final class HiddenFieldRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new HiddenFieldRule(
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\LocalAssignmentCollector(),
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\ParamShadowDetector(),
        );
    }

    #[Test]
    public function reportsParameterShadowingProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/ParameterShadowsProperty.php'],
            [
                [
                    'Parameter $name in Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\HiddenFieldRule\\ParameterShadowsProperty::rename() shadows property of the same name. Rename to avoid the name collision.',
                    11,
                ],
            ],
            'A method parameter that shares a name with a property must be reported',
        );
    }

    #[Test]
    public function reportsLocalVariableShadowingProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/LocalVariableShadowsProperty.php'],
            [
                [
                    'Local variable $total in Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\HiddenFieldRule\\LocalVariableShadowsProperty::recalculate() shadows property of the same name. Rename to avoid the name collision.',
                    13,
                ],
            ],
            'A local variable that shares a name with a property must be reported',
        );
    }

    #[Test]
    public function passesForPromotedConstructorParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/PromotedConstructor.php'],
            [],
            'A promoted constructor parameter is the property, not a shadow',
        );
    }

    #[Test]
    public function passesForNonPromotedConstructorWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/ConstructorWithShadowOptOut.php'],
            [],
            'ignoreConstructorParameter defaults to true so constructor shadows are silent',
        );
    }

    #[Test]
    public function reportsStaticPropertyShadowedByParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/StaticPropertyShadow.php'],
            [
                [
                    'Parameter $counter in Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\HiddenFieldRule\\StaticPropertyShadow::increment() shadows property of the same name. Rename to avoid the name collision.',
                    11,
                ],
            ],
            'Static properties must count as shadowed names just like non-static ones',
        );
    }

    #[Test]
    public function passesWhenParameterMatchesInheritedPropertyOnly(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/ParentWithName.php',
                __DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/ParentPropertyNotFlagged.php',
            ],
            [],
            'A property inherited from a parent class is not flagged by default',
        );
    }

    #[Test]
    public function passesWhenParameterAndPropertyHaveDifferentNames(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/DifferentName.php'],
            [],
            'Differently named parameters must not be reported',
        );
    }

    #[Test]
    public function passesWhenSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/SuppressedShadow.php'],
            [],
            'A @phpstan-ignore haspadar.hiddenField comment must silence the report',
        );
    }

    #[Test]
    public function reportsLocalVariableOnceEvenWhenAssignedMultipleTimes(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/DuplicateLocalAssignments.php'],
            [
                [
                    'Local variable $total in Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\HiddenFieldRule\\DuplicateLocalAssignments::recompute() shadows property of the same name. Rename to avoid the name collision.',
                    13,
                ],
            ],
            'Multiple assignments to the same local name must be reported only once',
        );
    }

    #[Test]
    public function passesWhenLocalIsDeclaredInsideNestedClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/NestedClosureShadow.php'],
            [],
            'Locals declared inside a nested closure belong to its scope and must not be flagged',
        );
    }

    #[Test]
    public function passesWhenLocalNameDoesNotMatchAnyProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/LocalNotMatchingProperty.php'],
            [],
            'Local variables with names different from any property must pass',
        );
    }

    #[Test]
    public function reportsAbstractMethodParameterUnderDefaults(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/AbstractMethodWithParamShadow.php'],
            [
                [
                    'Parameter $name in Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\HiddenFieldRule\\AbstractMethodWithParamShadow::rename() shadows property of the same name. Rename to avoid the name collision.',
                    11,
                ],
            ],
            'Abstract method parameters are reported under default ignoreAbstractMethods=false',
        );
    }
}
