<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitLongTypeAliasRule;

final class ClassWithNoDocComment
{
    public function add(int $n): int
    {
        return $n + 1;
    }
}
