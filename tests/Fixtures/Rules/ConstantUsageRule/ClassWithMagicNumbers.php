<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithMagicNumbers
{
    public function calculate(int $amount): int
    {
        return $amount * 42;
    }

    public function threshold(): float
    {
        return 3.14;
    }
}
