<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class InsideClosure
{
    public function run(): \Closure
    {
        return function (): int {
            $result = 42;
            return $result;
        };
    }
}
