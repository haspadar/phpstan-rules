<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\BelowMinDependencies;

final class TooSmall
{
    public function run(OnlyDep $d): string
    {
        return $d->ping();
    }
}

final class OnlyDep
{
    public function ping(): string
    {
        return 'pong';
    }
}
