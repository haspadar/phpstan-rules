<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithMagicStrings
{
    public function status(string $value): bool
    {
        return $value === 'active';
    }

    public function role(): string
    {
        return 'admin';
    }
}
