<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithNullAssignmentToVariable
{
    public function demo(): void
    {
        $value = null;
    }
}
