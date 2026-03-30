<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

class ClassWithPrivateMethod
{
    private function secret(): string
    {
        return 'hidden';
    }

    protected function inner(): void
    {
    }
}
