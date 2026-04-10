<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class NameWithUnderscore
{
    public function run(string $user_name): void
    {
        echo $user_name;
    }
}
