<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithCompoundAssignment
{
    public function process(int $count): int
    {
        $count += 1;

        return $count;
    }
}
