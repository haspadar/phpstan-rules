<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class SingleAssignment
{
    public function run(): int
    {
        $a = 1;
        $b = 2;

        return $a + $b;
    }
}
