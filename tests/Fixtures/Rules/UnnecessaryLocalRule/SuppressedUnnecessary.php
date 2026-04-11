<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class SuppressedUnnecessary
{
    public function run(): int
    {
        $result = $this->calculate(); // @phpstan-ignore haspadar.unnecessaryLocal
        return $result;
    }

    private function calculate(): int
    {
        return 42;
    }
}
