<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ArrowFunctionVariable
{
    public function run(): void
    {
        $handler = static fn() => $x = 1;
        echo $handler();
    }
}
