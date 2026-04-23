<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithNullAssignmentToStaticProperty
{
    public static ?string $cache = '';

    public function reset(): void
    {
        self::$cache = null;
    }
}
