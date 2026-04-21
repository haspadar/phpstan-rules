<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitStaticMethodsRule;

use Haspadar\PHPStanRules\Rules\ProhibitStaticMethodsRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitStaticMethodsRule> */
final class ProhibitStaticMethodsRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new ProhibitStaticMethodsRule();
    }

    #[Test]
    public function reportsPublicStaticMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithPublicStaticMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\ClassWithPublicStaticMethod::create() is static. Static methods are prohibited.',
                    9,
                ],
            ],
            'Public static declaration must be reported as a static-method violation',
        );
    }

    #[Test]
    public function reportsPrivateStaticMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithPrivateStaticMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\ClassWithPrivateStaticMethod::helper() is static. Static methods are prohibited.',
                    9,
                ],
            ],
            'Private static helper must be reported because visibility no longer exempts static methods',
        );
    }

    #[Test]
    public function reportsProtectedStaticMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithProtectedStaticMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\ClassWithProtectedStaticMethod::helper() is static. Static methods are prohibited.',
                    9,
                ],
            ],
            'Protected static helper must be reported — all visibilities are covered',
        );
    }

    #[Test]
    public function passesWhenMethodIsInstance(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithInstanceMethod.php'],
            [],
            'Non-static methods must never be reported regardless of visibility',
        );
    }

    #[Test]
    public function reportsEveryStaticMethodIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithMultipleStaticMethods.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\ClassWithMultipleStaticMethods::create() is static. Static methods are prohibited.',
                    9,
                ],
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\ClassWithMultipleStaticMethods::middleware() is static. Static methods are prohibited.',
                    14,
                ],
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule\ClassWithMultipleStaticMethods::helper() is static. Static methods are prohibited.',
                    19,
                ],
            ],
            'Every static method declaration must produce a separate error across all visibilities',
        );
    }

    #[Test]
    public function reportsStaticMethodInAnonymousClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/AnonymousClassWithStaticMethod.php'],
            [
                [
                    'Method AnonymousClassddfab24a22309f41108af6ca23a032dc::create() is static. Static methods are prohibited.',
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
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/SuppressedClassWithStaticMethod.php'],
            [],
            'A @phpstan-ignore haspadar.staticMethod comment must silence the new identifier',
        );
    }
}
