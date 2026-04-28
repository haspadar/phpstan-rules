<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ExplicitInitializationRule;

final class NullableProperties
{
    private ?string $name = null;

    private ?\stdClass $obj = null;

    private ?int $count = null;
}
