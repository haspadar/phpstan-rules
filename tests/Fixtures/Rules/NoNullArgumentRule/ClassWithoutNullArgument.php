<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullArgumentRule;

function nonNullTarget(string $name, int $age): string
{
    return sprintf('%s %d', $name, $age);
}

final class ClassWithoutNullArgument
{
    public function run(): string
    {
        return nonNullTarget('Alice', 30);
    }
}
