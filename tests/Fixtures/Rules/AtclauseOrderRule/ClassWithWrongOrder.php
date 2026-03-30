<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithWrongOrder
{
    /**
     * Saves the user.
     *
     * @param string $name The name.
     *
     * @throws \RuntimeException When saving fails.
     *
     * @return void
     */
    public function save(string $name): void
    {
    }
}
