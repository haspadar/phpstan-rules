<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

function namedArgumentTarget(?string $name = null, ?int $age = null): string
{
    return sprintf('%s %d', $name ?? '', $age ?? 0);
}

final class ClassWithNamedNullArgument
{
    public function run(): string
    {
        return namedArgumentTarget(name: null);
    }
}
