<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class StaticCallTarget
{
    public static function accept(?string $value): string
    {
        return $value ?? '';
    }
}
