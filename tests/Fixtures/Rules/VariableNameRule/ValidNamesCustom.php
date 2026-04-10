<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ValidNamesCustom
{
    public function run(): void
    {
        $name = 'Alice';
        $userId = 42;
        $id = 1;
    }
}
