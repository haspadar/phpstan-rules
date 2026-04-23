<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class SuppressedNullableProperty
{
    /** @phpstan-ignore haspadar.noNullableProperty */
    public ?string $name = '';
}
