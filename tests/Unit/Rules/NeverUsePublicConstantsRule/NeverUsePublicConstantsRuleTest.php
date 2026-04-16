<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NeverUsePublicConstantsRule;

use Haspadar\PHPStanRules\Rules\NeverUsePublicConstantsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NeverUsePublicConstantsRule> */
final class NeverUsePublicConstantsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NeverUsePublicConstantsRule();
    }

    #[Test]
    public function reportsErrorForPublicConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/ClassWithPublicConst.php'],
            [
                ['Constant NAME in class ClassWithPublicConst must not be public. Use private or protected visibility.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorForImplicitPublicConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/ClassWithImplicitPublicConst.php'],
            [
                ['Constant NAME in class ClassWithImplicitPublicConst must not be public. Use private or protected visibility.', 9],
            ],
        );
    }

    #[Test]
    public function reportsErrorForMultiplePublicConsts(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/ClassWithMultiplePublicConsts.php'],
            [
                ['Constant FIRST in class ClassWithMultiplePublicConsts must not be public. Use private or protected visibility.', 9],
                ['Constant SECOND in class ClassWithMultiplePublicConsts must not be public. Use private or protected visibility.', 10],
            ],
        );
    }

    #[Test]
    public function reportsErrorForAbstractClassWithPublicConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/AbstractClassWithPublicConst.php'],
            [
                ['Constant NAME in class AbstractClassWithPublicConst must not be public. Use private or protected visibility.', 9],
            ],
        );
    }

    #[Test]
    public function passesForPrivateConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/ClassWithPrivateConst.php'],
            [],
        );
    }

    #[Test]
    public function passesForProtectedConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/ClassWithProtectedConst.php'],
            [],
        );
    }

    #[Test]
    public function reportsOnlyPublicConstWhenMixedVisibility(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/ClassWithMixedVisibilityConsts.php'],
            [
                ['Constant PUBLIC_ONE in class ClassWithMixedVisibilityConsts must not be public. Use private or protected visibility.', 9],
            ],
        );
    }

    #[Test]
    public function passesForInterfaceWithConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/InterfaceWithConst.php'],
            [],
        );
    }

    #[Test]
    public function passesForEnumWithConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/EnumWithConst.php'],
            [],
        );
    }

    #[Test]
    public function passesForAnonymousClassWithConst(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/AnonymousClassWithConst.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NeverUsePublicConstantsRule/SuppressedPublicConst.php'],
            [],
        );
    }
}
