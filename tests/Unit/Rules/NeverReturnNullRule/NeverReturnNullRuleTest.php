<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NeverReturnNullRule;

use Haspadar\PHPStanRules\Rules\NeverReturnNullRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NeverReturnNullRule> */
final class NeverReturnNullRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NeverReturnNullRule();
    }

    #[Test]
    public function passesWhenReturnTypeIsNotNullable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/MethodWithoutNullableReturn.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForNullableReturnType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/MethodWithNullableReturn.php'],
            [
                ['Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule\MethodWithNullableReturn::greet() must not have a nullable return type.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorForUnionNullReturnType(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/MethodWithUnionNullReturn.php'],
            [
                ['Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule\MethodWithUnionNullReturn::greet() must not have a nullable return type.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorForReturnNullStatement(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/MethodWithReturnNull.php'],
            [
                ['Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule\MethodWithReturnNull::greet() must not return null.', 12],
            ],
        );
    }

    #[Test]
    public function reportsErrorForNullableFunctionReturn(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/FunctionWithNullableReturn.php'],
            [
                ['Function Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule\findName() must not have a nullable return type.', 7],
            ],
        );
    }

    #[Test]
    public function passesForClosureWithNullableReturn(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/ClosureWithNullableReturn.php'],
            [],
        );
    }

    #[Test]
    public function passesForArrowFunctionWithNullableReturn(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/ArrowFunctionWithNullableReturn.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverReturnNullRule/SuppressedNullableReturn.php'],
            [],
        );
    }
}
