<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithNullablePropertyShortSyntax
{
    public ?string $name = '';
}
