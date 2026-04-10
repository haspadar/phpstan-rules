<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ClosureVariable
{
    public function run(): void
    {
        $handler = static function (): void {
            $x = 1;
            echo $x;
        };
        $handler();
    }
}
