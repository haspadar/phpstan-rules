<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class ClassWithMultipleMissingParams
{
    /**
     * Combines three parts.
     *
     * @param string $first First chunk.
     */
    public function combine(string $first, string $second, string $third): string
    {
        return $first . $second . $third;
    }
}
