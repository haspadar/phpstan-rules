<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingMethodRule;

class ClassWithOverriddenMethod
{
    #[\Override]
    public function toString(): string
    {
        return 'value';
    }
}
