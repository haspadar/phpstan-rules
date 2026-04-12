<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithNegativeNumber
{
    public function offset(): int
    {
        return -42;
    }
}
