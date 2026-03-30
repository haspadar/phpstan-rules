<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithThrowsFirst
{
    /**
     * Saves the user.
     *
     * @throws \RuntimeException When saving fails.
     *
     * @param string $name The name.
     *
     * @return void
     */
    public function save(string $name): void
    {
    }
}
