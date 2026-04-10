<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class ClosureParameter
{
    public function run(): void
    {
        $handler = static function (string $x): string {
            return $x;
        };
        echo $handler('test');
    }
}
