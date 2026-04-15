<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NeverAcceptNullArgumentsRule;

use Haspadar\PHPStanRules\Rules\NeverAcceptNullArgumentsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NeverAcceptNullArgumentsRule> */
final class NeverAcceptNullArgumentsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NeverAcceptNullArgumentsRule();
    }

    #[Test]
    public function passesWhenParameterIsNotNullable(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/MethodWithoutNullableParam.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorForNullableTypeParam(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/MethodWithNullableParam.php'],
            [
                ['Parameter $name in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule\MethodWithNullableParam::greet() must not be nullable.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorForUnionNullParam(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/MethodWithUnionNull.php'],
            [
                ['Parameter $name in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule\MethodWithUnionNull::greet() must not be nullable.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorForNullDefaultParam(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/MethodWithNullDefault.php'],
            [
                ['Parameter $name in method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule\MethodWithNullDefault::greet() must not be nullable.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorForNullableFunctionParam(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/FunctionWithNullableParam.php'],
            [
                ['Parameter $name in function Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule\greetNullable() must not be nullable.', 7],
            ],
        );
    }

    #[Test]
    public function passesForClosureWithNullableParam(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/ClosureWithNullableParam.php'],
            [],
        );
    }

    #[Test]
    public function passesForArrowFunctionWithNullableParam(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/ArrowFunctionWithNullableParam.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverAcceptNullArgumentsRule/SuppressedNullableParam.php'],
            [],
        );
    }
}
