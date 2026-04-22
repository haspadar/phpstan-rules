<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamDescriptionRule;

final class ClassWithNoPhpDoc
{
    public function greet(string $name): string
    {
        return 'hello ' . $name;
    }
}
