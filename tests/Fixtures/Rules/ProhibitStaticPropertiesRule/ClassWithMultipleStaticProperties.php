<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

class ClassWithMultipleStaticProperties
{
    public static int $count = 0;

    protected static string $cache = '';

    private static ?self $instance = null;
}
