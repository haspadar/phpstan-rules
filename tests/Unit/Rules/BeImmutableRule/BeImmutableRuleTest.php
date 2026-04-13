<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\BeImmutableRule;

use Haspadar\PHPStanRules\Rules\BeImmutableRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<BeImmutableRule> */
final class BeImmutableRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new BeImmutableRule();
    }

    #[Test]
    public function reportsErrorWhenPropertyIsNotReadonly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ClassWithMutableProperty.php'],
            [
                ['Property $name in class ClassWithMutableProperty must be readonly to ensure immutability.', 9],
            ],
        );
    }

    #[Test]
    public function passesWhenPropertyIsReadonly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ClassWithReadonlyProperty.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenClassIsReadonly(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ReadonlyClass.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenPropertyIsStatic(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ClassWithStaticProperty.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenClassIsAbstract(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/AbstractClassWithMutableProperty.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/SuppressedClass.php'],
            [],
        );
    }
}
