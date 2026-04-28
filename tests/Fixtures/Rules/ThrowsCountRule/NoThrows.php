<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ThrowsCountRule;

final class NoThrows
{
    /**
     * Does not throw anything.
     */
    public function run(): void {}

    public function runWithoutDoc(): void {}
}
