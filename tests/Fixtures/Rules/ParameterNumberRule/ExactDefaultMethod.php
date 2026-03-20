<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNumberRule;

class ExactDefaultMethod
{
    public function create(string $name, string $email, string $role): void
    {
    }
}
