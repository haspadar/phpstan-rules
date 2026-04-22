<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class ClassWithPrivateMethod
{
    /**
     * Normalises the input.
     */
    private function normalise(string $input): string
    {
        return trim($input);
    }
}
