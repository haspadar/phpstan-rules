<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoNullArgumentRule;

use Haspadar\PHPStanRules\Rules\Internal\BuiltinCallDetector;
use Haspadar\PHPStanRules\Rules\NoNullArgumentRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoNullArgumentRule> */
final class NoNullArgumentRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new NoNullArgumentRule(new BuiltinCallDetector($this->createReflectionProvider()));
    }

    #[Test]
    public function reportsNullArgumentInFunctionCall(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullArgumentInFunctionCall.php'],
            [
                ['Passing null as argument #0 to function userDefinedGreet() is prohibited. Model absence explicitly (Null Object, Optional).', 16],
            ],
            'Passing null to a user-defined function must be reported',
        );
    }

    #[Test]
    public function reportsNullArgumentInMethodCall(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullArgumentInMethodCall.php'],
            [
                ['Passing null as argument #0 to method accept() is prohibited. Model absence explicitly (Null Object, Optional).', 11],
            ],
            'Passing null to an instance method must be reported',
        );
    }

    #[Test]
    public function reportsNullArgumentInNullsafeMethodCall(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullArgumentInNullsafeMethodCall.php'],
            [
                ['Passing null as argument #0 to method accept() is prohibited. Model absence explicitly (Null Object, Optional).', 11],
            ],
            'Passing null to a nullsafe method call must be reported',
        );
    }

    #[Test]
    public function reportsNullArgumentInNullsafeInsideCompoundExpression(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullsafeInCompoundExpression.php'],
            [
                ['Passing null as argument #0 to method accept() is prohibited. Model absence explicitly (Null Object, Optional).', 11],
            ],
            'A nullsafe method call embedded in a larger expression must still produce the error exactly once',
        );
    }

    #[Test]
    public function reportsNullArgumentInStaticCall(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullArgumentInStaticCall.php'],
            [
                ['Passing null as argument #0 to method Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule\StaticCallTarget::accept() is prohibited. Model absence explicitly (Null Object, Optional).', 11],
            ],
            'Passing null to a static method call must be reported',
        );
    }

    #[Test]
    public function reportsNullArgumentInConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullArgumentInConstructor.php'],
            [
                ['Passing null as argument #0 to constructor Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule\ConstructorTarget is prohibited. Model absence explicitly (Null Object, Optional).', 11],
            ],
            'Passing null to a user-defined constructor must be reported',
        );
    }

    #[Test]
    public function reportsNamedNullArgument(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNamedNullArgument.php'],
            [
                ['Passing null as argument "name" to function namedArgumentTarget() is prohibited. Model absence explicitly (Null Object, Optional).', 16],
            ],
            'Named null arguments must be reported using their name label',
        );
    }

    #[Test]
    public function reportsEveryNullArgumentSeparately(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithMultipleNullArguments.php'],
            [
                ['Passing null as argument #0 to function multiArgumentTarget() is prohibited. Model absence explicitly (Null Object, Optional).', 16],
                ['Passing null as argument #1 to function multiArgumentTarget() is prohibited. Model absence explicitly (Null Object, Optional).', 16],
                ['Passing null as argument #2 to function multiArgumentTarget() is prohibited. Model absence explicitly (Null Object, Optional).', 16],
            ],
            'Every null argument in the same call must produce a separate error',
        );
    }

    #[Test]
    public function passesWhenCallIsInternal(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullArgumentInInternalCall.php'],
            [],
            'Calls to PHP built-in functions must not be flagged',
        );
    }

    #[Test]
    public function passesWhenArgumentsAreNotNull(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithoutNullArgument.php'],
            [],
            'Calls without null arguments must never be reported',
        );
    }

    #[Test]
    public function passesWhenNullIsOutsideCall(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/ClassWithNullLiteralOutsideCall.php'],
            [],
            'Null literals outside call expressions belong to other rules',
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoNullArgumentRule/SuppressedNullArgument.php'],
            [],
            'A @phpstan-ignore haspadar.noNullArgument comment must silence the error',
        );
    }
}
