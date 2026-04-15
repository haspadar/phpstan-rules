<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

final class SuppressedNullableReturn
{
    /** @phpstan-ignore haspadar.noNullReturn */
    public function greet(): ?string
    {
        return null; // @phpstan-ignore haspadar.noNullReturn
    }
}
