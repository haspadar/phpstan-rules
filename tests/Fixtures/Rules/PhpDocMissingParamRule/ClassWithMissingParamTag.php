<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class ClassWithMissingParamTag
{
    /**
     * Greets a person.
     */
    public function greet(string $name): string
    {
        return 'hello ' . $name;
    }
}
