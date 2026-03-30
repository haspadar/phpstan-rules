<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

class SuppressedMethod
{
    // @phpstan-ignore haspadar.phpdocMissingMethod
    public function greet(): string
    {
        return 'Hello';
    }
}
