<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithNegativeParameterDefault
{
    public function offset(int $value = -42): int
    {
        return $value;
    }
}
