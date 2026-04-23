<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithoutNullAssignment
{
    public function demo(): int
    {
        $value = 42;
        return $value;
    }
}
