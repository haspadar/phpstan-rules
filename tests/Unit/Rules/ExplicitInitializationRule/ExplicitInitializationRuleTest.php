<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ExplicitInitializationRule;

use Haspadar\PHPStanRules\Rules\ExplicitInitializationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ExplicitInitializationRule> */
final class ExplicitInitializationRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ExplicitInitializationRule();
    }

    #[Test]
    public function reportsNullablePropertiesWithNullDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ExplicitInitializationRule/NullableProperties.php'],
            [
                [
                    'Property $name is explicitly initialized to its default value.',
                    9,
                ],
                [
                    'Property $obj is explicitly initialized to its default value.',
                    11,
                ],
                [
                    'Property $count is explicitly initialized to its default value.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function reportsUnionNullablePropertiesWithNullDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ExplicitInitializationRule/UnionNullableProperties.php'],
            [
                [
                    'Property $name is explicitly initialized to its default value.',
                    9,
                ],
                [
                    'Property $obj is explicitly initialized to its default value.',
                    11,
                ],
                [
                    'Property $count is explicitly initialized to its default value.',
                    13,
                ],
            ],
        );
    }

    #[Test]
    public function passesForPrimitivePropertiesWithZeroDefaults(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ExplicitInitializationRule/PrimitiveProperties.php'],
            [],
        );
    }

    #[Test]
    public function passesForNonRedundantDefaults(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ExplicitInitializationRule/ValidProperties.php'],
            [],
        );
    }

    #[Test]
    public function suppressesViolationWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ExplicitInitializationRule/SuppressedClass.php'],
            [],
        );
    }
}
