<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use PHPStan\ShouldNotHappenException;

/**
 * Compiles a user-provided regex pattern into a safe delimited form.
 * Uses ~ as delimiter, escaping any ~ in the pattern.
 * Validates the pattern at compile time and throws on invalid regex.
 */
final class CompiledPattern
{
    /**
     * Compiles the given pattern with a safe delimiter.
     *
     * @throws ShouldNotHappenException
     * @return non-empty-string
     */
    public function from(string $pattern, string $context): string
    {
        $compiled = sprintf('~%s~', str_replace('~', '\~', $pattern));

        if (@preg_match($compiled, '') === false) {
            throw new ShouldNotHappenException(
                sprintf('Invalid %s pattern "%s".', $context, $pattern),
            );
        }

        return $compiled;
    }
}
