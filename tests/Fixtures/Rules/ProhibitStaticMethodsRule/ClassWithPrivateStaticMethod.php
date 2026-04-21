<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitPublicStaticMethodsRule;

final class ClassWithPrivateStaticMethod
{
    private static function helper(): string
    {
        return 'ok';
    }
}
