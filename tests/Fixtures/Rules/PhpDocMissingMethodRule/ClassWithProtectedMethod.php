<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

class ClassWithProtectedMethod
{
    protected function inner(): void
    {
    }
}
