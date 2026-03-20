<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNumberRule;

class SuppressedLongMethod
{
    /** @phpstan-ignore haspadar.parameterNumber */
    public function create(string $name, string $email, string $role, string $phone): void
    {
    }
}
