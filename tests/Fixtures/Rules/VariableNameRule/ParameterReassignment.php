<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ParameterReassignment
{
    public function run(string $n): void
    {
        $n = strtoupper($n);
        echo $n;
    }
}
