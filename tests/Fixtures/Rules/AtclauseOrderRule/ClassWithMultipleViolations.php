<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithMultipleViolations
{
    /**
     * Saves the user.
     *
     * @throws \LogicException When saving fails.
     *
     * @return void
     *
     * @param string $name The name.
     */
    public function save(string $name): void
    {
    }
}
