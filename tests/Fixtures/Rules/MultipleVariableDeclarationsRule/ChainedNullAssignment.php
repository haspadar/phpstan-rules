<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class ChainedNullAssignment
{
    public function run(): bool
    {
        $a = $b = null;
        $c = $d = null;

        return $a === null && $b === null && $c === null && $d === null;
    }
}
