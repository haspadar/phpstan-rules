<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ExplicitInitializationRule;

final class UnionNullableProperties
{
    private string|null $name = null;

    private \stdClass|null $obj = null;

    private int|null $count = null;
}
