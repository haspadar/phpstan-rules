<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithFunctionCallInConstructor
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = strtoupper($name);
    }
}
