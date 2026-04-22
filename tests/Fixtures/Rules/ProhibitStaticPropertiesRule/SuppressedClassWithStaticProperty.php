<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

final class SuppressedClassWithStaticProperty
{
    /** @phpstan-ignore haspadar.staticProperty */
    private static array $cache = [];
}
