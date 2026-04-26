<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class ChainedAssignment
{
    public function run(): int
    {
        print 'header';
        $a = $b = 1;
        $second = 2;
        $c = $d = 2;

        return $a + $b + $c + $d + $second;
    }
}
