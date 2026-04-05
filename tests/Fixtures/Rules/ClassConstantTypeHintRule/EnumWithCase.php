<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassConstantTypeHintRule;

enum EnumWithCase: string
{
    case FOO = 'bar';
    case BAZ = 'qux';
}
