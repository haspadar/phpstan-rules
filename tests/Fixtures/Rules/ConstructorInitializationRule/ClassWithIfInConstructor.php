<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstructorInitializationRule;

final class ClassWithIfInConstructor
{
    private string $name;

    public function __construct(string $name)
    {
        if ($name === '') {
            $name = 'default';
        }

        $this->name = $name;
    }
}
