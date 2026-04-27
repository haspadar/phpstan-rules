<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NestedForDepthRule;

abstract class AbstractMethod
{
    abstract public function run(array $items): array;
}
