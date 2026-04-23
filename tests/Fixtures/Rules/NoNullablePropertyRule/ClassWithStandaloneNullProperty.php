<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithStandaloneNullProperty
{
    public null $value = null;
}
