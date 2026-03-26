<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithPropertyAssignment
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
