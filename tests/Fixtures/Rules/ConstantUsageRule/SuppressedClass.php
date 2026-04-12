<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class SuppressedClass
{
    public function magic(): int
    {
        return 42; // @phpstan-ignore haspadar.constantUsage
    }
}
