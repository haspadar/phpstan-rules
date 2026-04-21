<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

class ClassWithMultipleStaticMethods
{
    public static function create(): self
    {
        return new self();
    }

    protected static function middleware(): string
    {
        return 'mw';
    }

    private static function helper(): string
    {
        return 'h';
    }
}
