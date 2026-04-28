<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ExplicitInitializationRule;

final class SuppressedClass
{
    /** @phpstan-ignore haspadar.explicitInit */
    private ?string $name = null;

    /** @phpstan-ignore haspadar.explicitInit */
    private int $count = 0;
}
