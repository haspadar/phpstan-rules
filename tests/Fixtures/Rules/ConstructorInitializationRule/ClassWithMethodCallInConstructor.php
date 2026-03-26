<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithMethodCallInConstructor
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->validate($name);
    }

    private function validate(string $name): void
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
    }
}
