<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithPrivateMethod
{
    /**
     * Normalises two inputs.
     *
     * @param string $second Second.
     * @param string $first First.
     */
    private function normalise(string $first, string $second): string
    {
        return trim($first . $second);
    }
}
