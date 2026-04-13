<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BeImmutableRule;

final readonly class ReadonlyClass
{
    public function __construct(
        private string $name,
        private int $age,
    ) {
    }
}
