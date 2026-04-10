<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class TooLongName
{
    public function run(): void
    {
        $extremelyLongVariableName = 1;
    }
}
