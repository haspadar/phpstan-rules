<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

final class ClosureWithNullableParam
{
    public function run(): string
    {
        $fn = static function (?string $name): string {
            return $name ?? 'world';
        };

        return $fn('hello');
    }
}
