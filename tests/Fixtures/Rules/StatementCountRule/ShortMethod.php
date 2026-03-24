<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class ShortMethod
{
    public function run(bool $a): bool
    {
        $b = !$a;
        return $b;
    }
}
