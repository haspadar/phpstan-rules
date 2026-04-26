<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedIfDepthRule;

abstract class AbstractMethod
{
    abstract public function run(int $value): int;
}
