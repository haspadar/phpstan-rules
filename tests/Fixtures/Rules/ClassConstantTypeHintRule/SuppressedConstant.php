<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassConstantTypeHintRule;

final class SuppressedConstant
{
    /** @phpstan-ignore haspadar.classConstantType */
    public const FOO = 'bar';
}
