<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverReturnNullRule;

abstract class AbstractMethodWithNullableReturn
{
    abstract public function greet(): ?string;
}
