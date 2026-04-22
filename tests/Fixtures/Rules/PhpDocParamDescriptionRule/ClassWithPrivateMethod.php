<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class ClassWithPrivateMethod
{
    /**
     * Normalises the input.
     *
     * @param string $input
     */
    private function normalise(string $input): string
    {
        return trim($input);
    }
}
