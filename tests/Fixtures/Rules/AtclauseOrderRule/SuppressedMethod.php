<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class SuppressedMethod
{
    /**
     * Saves the user.
     *
     * @param string $name The name.
     *
     * @throws \RuntimeException When saving fails.
     *
     * @return void
     *
     * @phpstan-ignore haspadar.atclauseOrder
     */
    public function save(string $name): void
    {
    }
}
