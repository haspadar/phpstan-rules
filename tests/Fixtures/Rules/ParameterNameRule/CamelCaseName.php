<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class CamelCaseName
{
    public function run(string $userName): void
    {
        echo $userName;
    }
}
