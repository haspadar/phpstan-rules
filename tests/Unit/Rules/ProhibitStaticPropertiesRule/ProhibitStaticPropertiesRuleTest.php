<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitStaticPropertiesRule;

use Haspadar\PHPStanRules\Rules\ProhibitStaticPropertiesRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitStaticPropertiesRule> */
final class ProhibitStaticPropertiesRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new ProhibitStaticPropertiesRule();
    }

    #[Test]
    public function reportsPublicStaticProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/ClassWithPublicStaticProperty.php'],
            [
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithPublicStaticProperty::$count is static. Static properties are prohibited.',
                    9,
                ],
            ],
            'Public static property declaration must be reported',
        );
    }

    #[Test]
    public function reportsPrivateStaticProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/ClassWithPrivateStaticProperty.php'],
            [
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithPrivateStaticProperty::$instance is static. Static properties are prohibited.',
                    9,
                ],
            ],
            'Private static property must be reported — visibility does not exempt static state',
        );
    }

    #[Test]
    public function reportsProtectedStaticProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/ClassWithProtectedStaticProperty.php'],
            [
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithProtectedStaticProperty::$cache is static. Static properties are prohibited.',
                    9,
                ],
            ],
            'Protected static property must be reported — all visibilities are covered',
        );
    }

    #[Test]
    public function passesWhenPropertyIsInstance(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/ClassWithInstanceProperty.php'],
            [],
            'Non-static properties must never be reported',
        );
    }

    #[Test]
    public function reportsEveryStaticPropertyIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/ClassWithMultipleStaticProperties.php'],
            [
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithMultipleStaticProperties::$count is static. Static properties are prohibited.',
                    9,
                ],
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithMultipleStaticProperties::$cache is static. Static properties are prohibited.',
                    11,
                ],
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithMultipleStaticProperties::$instance is static. Static properties are prohibited.',
                    13,
                ],
            ],
            'Every static property declaration across all visibilities must produce a separate error',
        );
    }

    #[Test]
    public function reportsEachPropertyInGroupedDeclaration(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/ClassWithGroupedStaticProperties.php'],
            [
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithGroupedStaticProperties::$first is static. Static properties are prohibited.',
                    9,
                ],
                [
                    'Property Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule\ClassWithGroupedStaticProperties::$second is static. Static properties are prohibited.',
                    9,
                ],
            ],
            'Every property in a grouped static declaration must produce its own error',
        );
    }

    #[Test]
    public function reportsStaticPropertyInAnonymousClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/AnonymousClassWithStaticProperty.php'],
            [
                [
                    'Property class@anonymous::$count is static. Static properties are prohibited.',
                    12,
                ],
            ],
            'Anonymous classes must be checked the same way as named ones',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/SuppressedClassWithStaticProperty.php'],
            [],
            'A @phpstan-ignore haspadar.staticProperty comment must silence the declaration error',
        );
    }

    #[Test]
    public function passesWhenAccessingVendorStaticProperty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticPropertiesRule/ClassWithStaticAccessToVendor.php'],
            [],
            'Static access to third-party classes is outside the scope of the declaration rule',
        );
    }
}
