<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

function suppressedTarget(?string $value): string
{
    return $value ?? '';
}

final class SuppressedNullArgument
{
    public function run(): string
    {
        /** @phpstan-ignore haspadar.noNullArgument */
        return suppressedTarget(null);
    }
}
