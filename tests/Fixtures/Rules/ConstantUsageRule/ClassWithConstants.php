<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithConstants
{
    private const int MULTIPLIER = 42;

    private const float PI = 3.14;

    private const string STATUS_ACTIVE = 'active';

    public function calculate(int $amount): int
    {
        return $amount * self::MULTIPLIER;
    }
}
