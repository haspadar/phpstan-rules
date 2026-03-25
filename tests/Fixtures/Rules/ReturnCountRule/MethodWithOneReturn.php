<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnCountRule;

final class MethodWithOneReturn
{
    public function find(int $id): int
    {
        return $id;
    }
}
