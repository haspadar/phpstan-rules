<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullablePropertyRule;

final class ClassWithThreePartUnionContainingNull
{
    public string|null|int $value = '';
}
