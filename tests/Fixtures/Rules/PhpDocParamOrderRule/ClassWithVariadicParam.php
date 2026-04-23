<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithVariadicParam
{
    /**
     * Concatenates a prefix with parts.
     *
     * @param string    ...$parts Parts to concatenate.
     * @param string    $prefix   Prefix placed before the parts.
     */
    public function concat(string $prefix, string ...$parts): string
    {
        return $prefix . implode('', $parts);
    }
}
