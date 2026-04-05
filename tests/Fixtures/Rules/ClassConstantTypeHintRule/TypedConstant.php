<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassConstantTypeHintRule;

final class TypedConstant
{
    public const string FOO = 'bar';
    public const int BAZ = 42;
}
