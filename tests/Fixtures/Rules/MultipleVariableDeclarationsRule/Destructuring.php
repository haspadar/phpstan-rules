<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MultipleVariableDeclarationsRule;

final class Destructuring
{
    /**
     * @return array{0: int, 1: int}
     */
    public function pair(): array
    {
        $tuple = [1, 2];
        [$a, $b] = $tuple;

        return [$a, $b];
    }
}
