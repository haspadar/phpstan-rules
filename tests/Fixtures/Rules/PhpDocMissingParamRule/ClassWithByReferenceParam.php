<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingParamRule;

final class ClassWithByReferenceParam
{
    /**
     * Appends to the buffer.
     */
    public function append(string &$buffer): void
    {
        $buffer .= '!';
    }
}
