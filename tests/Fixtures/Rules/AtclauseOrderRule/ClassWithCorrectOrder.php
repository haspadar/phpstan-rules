<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithCorrectOrder
{
    /**
     * Saves the user.
     *
     * @param string $name The name.
     *
     * @return void
     *
     * @throws \RuntimeException When saving fails.
     */
    public function save(string $name): void
    {
    }
}
