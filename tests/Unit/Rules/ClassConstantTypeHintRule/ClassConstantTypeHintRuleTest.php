<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ClassConstantTypeHintRule;

use Haspadar\PHPStanRules\Rules\ClassConstantTypeHintRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ClassConstantTypeHintRule> */
final class ClassConstantTypeHintRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ClassConstantTypeHintRule();
    }

    #[Test]
    public function passesWhenConstantHasType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassConstantTypeHintRule/TypedConstant.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenConstantHasNoType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassConstantTypeHintRule/UntypedConstant.php'],
            [
                [
                    'Constant Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassConstantTypeHintRule\UntypedConstant::FOO must have a native type declaration.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesForEnumCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassConstantTypeHintRule/EnumWithCase.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForInterfaceConstant(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassConstantTypeHintRule/InterfaceUntypedConstant.php'],
            [
                [
                    'Constant Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassConstantTypeHintRule\InterfaceUntypedConstant::FOO must have a native type declaration.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function reportsOnlyUntypedWhenMixed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassConstantTypeHintRule/MultipleConstants.php'],
            [
                [
                    'Constant Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassConstantTypeHintRule\MultipleConstants::UNTYPED must have a native type declaration.',
                    10,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassConstantTypeHintRule/SuppressedConstant.php'],
            [],
        );
    }
}
