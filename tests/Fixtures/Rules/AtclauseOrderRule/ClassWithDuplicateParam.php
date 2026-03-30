<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithDuplicateParam
{
    /**
     * Saves the user.
     *
     * @param string $name The name.
     *
     * @param string $email The email.
     *
     * @return void
     */
    public function save(string $name, string $email): void
    {
    }
}
