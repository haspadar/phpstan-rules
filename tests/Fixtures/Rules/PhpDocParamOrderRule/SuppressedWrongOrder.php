<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocParamOrderRule;

final class SuppressedWrongOrder
{
    /**
     * Adds two numbers.
     *
     * @param int $b Second.
     * @param int $a First.
     * @phpstan-ignore haspadar.phpdocParamOrder
     */
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
