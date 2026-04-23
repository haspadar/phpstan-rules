<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class ClassWithNoPhpDoc
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
