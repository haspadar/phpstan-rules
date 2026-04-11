<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class UnnecessaryBeforeReturn
{
    public function run(): int
    {
        $result = $this->calculate();
        return $result;
    }

    private function calculate(): int
    {
        return 42;
    }
}
