<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ClassConstantTypeHintRule;

final class MultipleConstants
{
    public const string TYPED = 'hello';
    public const UNTYPED = 'world';
}
