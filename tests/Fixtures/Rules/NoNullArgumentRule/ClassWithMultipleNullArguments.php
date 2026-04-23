<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

function multiArgumentTarget(?string $a, ?int $b, ?float $c): string
{
    return sprintf('%s %d %.1f', $a ?? '', $b ?? 0, $c ?? 0.0);
}

final class ClassWithMultipleNullArguments
{
    public function run(): string
    {
        return multiArgumentTarget(null, null, null);
    }
}
