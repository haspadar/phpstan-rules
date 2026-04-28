<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitStaticMethodsRule;

use Haspadar\PHPStanRules\Rules\ProhibitStaticMethodsRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitStaticMethodsRule> */
final class ProhibitStaticMethodsRuleOnlyPublicTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new ProhibitStaticMethodsRule(['onlyPublic' => true]);
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
            'Public static methods must still be reported when onlyPublic=true',
        );
    }

    #[Test]
    public function passesWhenPrivateStaticMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithPrivateStaticMethod.php'],
            [],
            'Private static methods must be allowed when onlyPublic=true',
        );
    }

    #[Test]
    public function passesWhenProtectedStaticMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithProtectedStaticMethod.php'],
            [],
            'Protected static methods must be allowed when onlyPublic=true',
        );
    }
}
