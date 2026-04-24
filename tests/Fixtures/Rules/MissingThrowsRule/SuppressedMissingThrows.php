<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MissingThrowsRule;

final class SuppressedMissingThrows
{
    public function run(): void
    {
        /** @phpstan-ignore haspadar.missingThrows */
        throw new \RuntimeException();
    }
}
