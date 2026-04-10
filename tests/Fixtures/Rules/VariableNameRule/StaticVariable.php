<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class StaticVariable
{
    public function run(): void
    {
        static $x = 0;
        echo $x;
    }
}
