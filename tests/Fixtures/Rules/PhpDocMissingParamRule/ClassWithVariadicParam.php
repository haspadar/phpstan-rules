<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class ClassWithVariadicParam
{
    /**
     * Concatenates parts.
     */
    public function concat(string ...$parts): string
    {
        return implode('', $parts);
    }
}
