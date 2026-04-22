<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class ClassWithMultipleEmptyDescriptions
{
    /**
     * Combines three parts.
     *
     * @param string $first
     * @param string $second
     * @param string $third
     */
    public function combine(string $first, string $second, string $third): string
    {
        return $first . $second . $third;
    }
}
