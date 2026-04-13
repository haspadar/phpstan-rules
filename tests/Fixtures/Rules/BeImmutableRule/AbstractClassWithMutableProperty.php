<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\BeImmutableRule;

abstract class AbstractClassWithMutableProperty
{
    protected string $name;
}
