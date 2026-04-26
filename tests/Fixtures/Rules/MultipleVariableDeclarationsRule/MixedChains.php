<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class MixedChains
{
    public function run(): bool
    {
        $a = $b = null;
        $c = $d = 1;
        $e = $f = null;

        return $a === null && $b === null && $c === 1 && $d === 1 && $e === null && $f === null;
    }
}
