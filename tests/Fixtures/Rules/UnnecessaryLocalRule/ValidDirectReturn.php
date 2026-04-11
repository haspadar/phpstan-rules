<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class ValidDirectReturn
{
    public function run(): int
    {
        return $this->calculate();
    }

    private function calculate(): int
    {
        return 42;
    }
}
