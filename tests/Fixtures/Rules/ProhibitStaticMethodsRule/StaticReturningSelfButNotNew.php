<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticMethodsRule;

final class StaticReturningSelfButNotNew
{
    private static self $instance;

    public function __construct(private readonly int $id)
    {
    }

    public static function shared(): self
    {
        return self::$instance;
    }
}
