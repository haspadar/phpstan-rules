<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class WithVarPhpDoc
{
    public function run(): int
    {
        /** @var int $result */
        $result = $this->calculate();
        return $result;
    }

    private function calculate(): mixed
    {
        return 42;
    }
}
