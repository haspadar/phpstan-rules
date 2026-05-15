<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ProhibitStaticMethodsRule;

use Haspadar\PHPStanRules\Rules\ProhibitStaticMethodsRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ProhibitStaticMethodsRule> */
final class ProhibitStaticMethodsRuleOnlyPublicAndNamedConstructorsTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new ProhibitStaticMethodsRule([
            'onlyPublic' => true,
            'allowNamedConstructors' => true,
        ]);
    }

    #[Test]
    public function passesWhenPublicNamedConstructorAndPrivateStaticHelperCoexist(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/PublicNamedConstructorWithPrivateStaticHelper.php'],
            [],
            'onlyPublic must exempt private static helpers and allowNamedConstructors must permit the public named constructor',
        );
    }

    #[Test]
    public function reportsPublicStaticHelperWhenNotNamedConstructor(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ProhibitStaticMethodsRule/ClassWithPublicStaticMethod.php'],
            [],
            'Public static method whose body is `return new self()` is a valid named constructor and must pass',
        );
    }
}
