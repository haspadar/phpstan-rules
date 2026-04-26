<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class SuppressedChained
{
    public function run(): int
    {
        /** @phpstan-ignore haspadar.multipleVarDecl */
        $a = $b = 1;

        return $a + $b;
    }
}
