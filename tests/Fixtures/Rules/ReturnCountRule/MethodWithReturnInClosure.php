<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnCountRule;

final class MethodWithReturnInClosure
{
    public function filter(int $id): int
    {
        $fn = static function (int $x): bool {
            return $x > 0;
        };

        return $id;
    }
}
