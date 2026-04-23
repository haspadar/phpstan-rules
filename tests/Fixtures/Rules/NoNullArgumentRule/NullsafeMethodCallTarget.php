<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

final class NullsafeMethodCallTarget
{
    public function accept(?string $value): string
    {
        return $value ?? '';
    }
}
