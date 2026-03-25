<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnCountRule;

final class SuppressedMethodWithTwoReturns
{
    /** @phpstan-ignore haspadar.returnCount */
    public function find(int $id): int
    {
        if ($id <= 0) {
            return 0;
        }

        return $id;
    }
}
