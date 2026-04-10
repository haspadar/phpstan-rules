<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class NameWithDigit
{
    public function run(): void
    {
        $item2 = 1;
    }
}
