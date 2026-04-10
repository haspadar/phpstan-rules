<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class AllowedName
{
    public function run(): void
    {
        $db = 'connection';
        echo $db;
    }
}
