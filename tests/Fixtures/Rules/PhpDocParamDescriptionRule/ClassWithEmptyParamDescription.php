<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class ClassWithEmptyParamDescription
{
    /**
     * Greets a person.
     *
     * @param string $name
     */
    public function greet(string $name): string
    {
        return 'hello ' . $name;
    }
}
