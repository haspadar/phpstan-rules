<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithTwoClosures
{
    /** @return array<\Closure> */
    public function run(): array
    {
        $first = static function (): void {
            $a = 1;
            $b = 2;
            $c = 3;
            $d = 4;
            $e = 5;
        };
        $second = static function (): void {
            $a = 1;
            $b = 2;
            $c = 3;
            $d = 4;
            $e = 5;
        };
        return [$first, $second];
    }
}
