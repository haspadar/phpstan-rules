<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithConstructorParameterReassignment
{
    private string $name;

    public function __construct(string $name)
    {
        $name = strtolower($name);
        $this->name = $name;
    }
}
