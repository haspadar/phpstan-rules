<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithMultipleNullableProperties
{
    public ?string $name = '';

    protected int|null $age = 0;

    private null|float $score = 0.0;
}
