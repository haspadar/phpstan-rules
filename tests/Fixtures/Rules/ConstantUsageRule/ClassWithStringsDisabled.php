<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithStringsDisabled
{
    public function role(): string
    {
        return 'admin';
    }
}
