<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnCountRule;

final class MethodWithReturnInArrowFunction
{
    public function filter(int $id): int
    {
        $fn = fn(int $x): bool => $x > 0;

        return $id;
    }
}
