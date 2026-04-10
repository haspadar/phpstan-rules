<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class DestructuringVariable
{
    public function run(): void
    {
        [$x, $name] = [1, 'Alice'];
        echo $x . $name;
    }
}
