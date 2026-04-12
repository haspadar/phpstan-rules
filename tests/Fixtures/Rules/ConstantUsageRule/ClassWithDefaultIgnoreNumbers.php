<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithDefaultIgnoreNumbers
{
    public function magic(): int
    {
        return 42;
    }

    public function zero(): int
    {
        return 0;
    }

    public function one(): int
    {
        return 1;
    }
}
