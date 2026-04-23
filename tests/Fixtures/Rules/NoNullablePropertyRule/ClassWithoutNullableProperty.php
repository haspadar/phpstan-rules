<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithoutNullableProperty
{
    public string $name = '';

    private int $age = 0;
}
