<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class SuppressedClassWithStaticMethod
{
    /** @phpstan-ignore haspadar.staticMethod */
    private static function helper(): string
    {
        return 'ok';
    }
}
