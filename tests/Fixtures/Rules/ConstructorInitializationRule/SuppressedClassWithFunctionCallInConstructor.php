<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class SuppressedClassWithFunctionCallInConstructor
{
    private string $name;

    public function __construct(string $name)
    {
        /** @phpstan-ignore haspadar.constructorInit */
        $this->name = strtoupper($name);
    }
}
