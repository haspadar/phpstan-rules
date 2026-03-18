<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLengthRule;

final class ShortMethodWithSpacedBlanks
{
    public function run(): string
    {
        $a = 1;
     
        $b = 2;

        return (string) ($a + $b);
    }
}
