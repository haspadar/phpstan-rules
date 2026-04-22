<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class ClassWithNoParameters
{
    /**
     * Returns a greeting.
     */
    public function greet(): string
    {
        return 'hello';
    }
}
