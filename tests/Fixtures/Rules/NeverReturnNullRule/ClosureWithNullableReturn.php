<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

final class ClosureWithNullableReturn
{
    public function run(): string
    {
        $fn = static function (): ?string {
            return null;
        };

        return $fn() ?? 'default';
    }
}
