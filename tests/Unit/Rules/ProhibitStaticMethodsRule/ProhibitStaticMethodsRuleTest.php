<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitPublicStaticMethodsRule;

use Haspadar\PHPStanRules\Rules\ProhibitPublicStaticMethodsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitPublicStaticMethodsRule> */
final class ProhibitPublicStaticMethodsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ProhibitPublicStaticMethodsRule();
    }

    #[Test]
    public function reportsErrorForPublicStaticMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitPublicStaticMethodsRule/ClassWithPublicStaticMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule\ClassWithPublicStaticMethod::create() is public static. Static methods are prohibited.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodIsPrivateStatic(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitPublicStaticMethodsRule/ClassWithPrivateStaticMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsPublicNonStatic(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitPublicStaticMethodsRule/ClassWithPublicMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsEachPublicStaticMethodIndependently(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitPublicStaticMethodsRule/ClassWithMultiplePublicStaticMethods.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule\ClassWithMultiplePublicStaticMethods::create() is public static. Static methods are prohibited.',
                    9,
                ],
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule\ClassWithMultiplePublicStaticMethods::empty() is public static. Static methods are prohibited.',
                    14,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenErrorIsSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitPublicStaticMethodsRule/SuppressedClassWithPublicStaticMethod.php'],
            [],
        );
    }


}
