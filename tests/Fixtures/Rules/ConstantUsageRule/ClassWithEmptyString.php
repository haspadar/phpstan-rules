<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithEmptyString
{
    public function fallback(): string
    {
        return '';
    }
}
