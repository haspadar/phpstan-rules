<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class InsideIfBlock
{
    public function run(bool $flag): int
    {
        if ($flag) {
            $result = 42;
            return $result;
        }

        return 0;
    }
}
