<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitStaticMethodsRule;

use Haspadar\PHPStanRules\Rules\ProhibitStaticMethodsRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitStaticMethodsRule> */
final class ProhibitStaticMethodsRuleAllowNamedConstructorsTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new ProhibitStaticMethodsRule(['allowNamedConstructors' => true]);
    }

    #[Test]
    public function passesWhenNamedConstructorReturnsNewSelf(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/NamedConstructorValid.php'],
            [],
            'Static method returning self with a single `return new self(...)` is a valid named constructor',
        );
    }

    #[Test]
    public function passesWhenNamedConstructorReturnsNewStatic(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/NamedConstructorValidStatic.php'],
            [],
            'Static method returning static with a single `return new static(...)` is a valid named constructor',
        );
    }

    #[Test]
    public function reportsNamedConstructorWithExtraLogic(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/NamedConstructorWithLogic.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\NamedConstructorWithLogic::fromArray() is not a valid named constructor: body must be a single `return new self(...)` or `return new static(...)`. Move logic to the primary __construct().',
                    13,
                ],
            ],
            'Static method returning self but containing extra statements must be flagged as invalid named constructor',
        );
    }

    #[Test]
    public function reportsStaticReturningSelfWithoutNew(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/StaticReturningSelfButNotNew.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\StaticReturningSelfButNotNew::shared() is not a valid named constructor: body must be a single `return new self(...)` or `return new static(...)`. Move logic to the primary __construct().',
                    15,
                ],
            ],
            'Static method returning self via cached instance is not a named constructor',
        );
    }

    #[Test]
    public function reportsStaticWithoutSelfReturnAsStandardViolation(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithPrivateStaticMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\ClassWithPrivateStaticMethod::helper() is static. Static methods are prohibited.',
                    9,
                ],
            ],
            'Static method without self/static return type must still trigger the standard prohibition',
        );
    }
}
