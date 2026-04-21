<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

class ClassWithProtectedStaticMethod
{
    protected static function helper(): string
    {
        return 'ok';
    }
}
