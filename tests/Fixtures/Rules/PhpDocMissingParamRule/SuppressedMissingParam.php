<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class SuppressedMissingParam
{
    /**
     * Greets a person.
     *
     * @phpstan-ignore haspadar.phpdocMissingParam
     */
    public function greet(string $name): string
    {
        return 'hello ' . $name;
    }
}
