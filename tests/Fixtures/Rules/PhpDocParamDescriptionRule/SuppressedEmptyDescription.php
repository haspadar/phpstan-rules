<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class SuppressedEmptyDescription
{
    /**
     * Greets a person.
     *
     * @param string $name
     * @phpstan-ignore haspadar.phpdocParamDescription
     */
    public function greet(string $name): string
    {
        return 'hello ' . $name;
    }
}
