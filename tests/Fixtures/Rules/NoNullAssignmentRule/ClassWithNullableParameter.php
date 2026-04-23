<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithNullableParameter
{
    public function greet(?string $name = null): string
    {
        return 'hello ' . ($name ?? 'world');
    }
}
