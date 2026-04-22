<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class ClassWithMixedDescriptions
{
    /**
     * Combines three parts.
     *
     * @param string $first First chunk.
     * @param string $second
     * @param string $third Third chunk.
     */
    public function combine(string $first, string $second, string $third): string
    {
        return $first . $second . $third;
    }
}
