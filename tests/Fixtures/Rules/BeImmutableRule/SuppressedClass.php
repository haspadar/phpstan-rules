<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BeImmutableRule;

final class SuppressedClass
{
    /** @phpstan-ignore haspadar.immutable */
    private string $name;
}
