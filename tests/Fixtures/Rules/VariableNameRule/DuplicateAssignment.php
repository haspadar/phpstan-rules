<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class DuplicateAssignment
{
    public function run(): void
    {
        $x = 1;
        $x = 2;
        echo $x;
    }
}
