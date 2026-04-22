<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class ClassWithVariadicParam
{
    /**
     * Concatenates parts.
     *
     * @param string ...$parts
     */
    public function concat(string ...$parts): string
    {
        return implode('', $parts);
    }
}
