<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class DeepChainedAssignment
{
    public function run(): int
    {
        $a = $b = $c = $d = 1;

        return $a + $b + $c + $d;
    }
}
