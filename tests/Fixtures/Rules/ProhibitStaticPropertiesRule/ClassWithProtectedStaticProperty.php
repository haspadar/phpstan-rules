<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

class ClassWithProtectedStaticProperty
{
    protected static string $cache = '';
}
