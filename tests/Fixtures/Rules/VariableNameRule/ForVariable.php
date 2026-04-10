<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class ForVariable
{
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            echo $i;
        }
    }
}
