<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithParamOnly
{
    /**
     * Saves the user.
     *
     * @param string $name The name.
     */
    public function save(string $name): void
    {
    }
}
