<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class MultipleReturns
{
    public function run(bool $flag): int
    {
        if ($flag) {
            return 0;
        }

        $result = $this->calculate();
        return $result;
    }

    private function calculate(): int
    {
        return 42;
    }
}
