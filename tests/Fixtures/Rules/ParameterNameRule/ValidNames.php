<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class ValidNames
{
    public function run(string $name, int $value, string $text): void
    {
        echo $name . $value . $text;
    }
}
