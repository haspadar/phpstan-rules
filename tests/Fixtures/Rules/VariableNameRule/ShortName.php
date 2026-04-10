<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ShortName
{
    public function run(): void
    {
        $x = 1;
    }
}
