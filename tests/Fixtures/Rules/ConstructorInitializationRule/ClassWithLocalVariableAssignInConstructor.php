<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithLocalVariableAssignInConstructor
{
    private string $name;

    public function __construct(string $name)
    {
        $normalized = $name;
        $this->name = $normalized;
    }
}
