<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class UsedElsewhere
{
    public function run(): int
    {
        $result = $this->calculate();
        $this->log($result);
        return $result;
    }

    private function calculate(): int
    {
        return 42;
    }

    private function log(int $value): void
    {
        echo $value;
    }
}
