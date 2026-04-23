<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithNullablePropertyUnionSyntax
{
    public string|null $name = '';
}
