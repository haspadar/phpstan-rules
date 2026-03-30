<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

class ClassWithMissingPhpDoc
{
    public function greet(): string
    {
        return 'Hello';
    }
}
