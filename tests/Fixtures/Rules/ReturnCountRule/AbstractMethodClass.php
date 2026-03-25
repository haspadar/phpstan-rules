<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnCountRule;

abstract class AbstractMethodClass
{
    abstract public function find(int $id): int;
}
