<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

final class ArrowFunctionWithNullableReturn
{
    public function run(): string
    {
        $fn = static fn(): ?string => null;

        return $fn() ?? 'default';
    }
}
