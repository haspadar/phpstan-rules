<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverAcceptNullArgumentsRule;

final class SuppressedNullableParam
{
    /** @phpstan-ignore haspadar.noNullArguments */
    public function greet(?string $name): string
    {
        return $name ?? 'world';
    }
}
