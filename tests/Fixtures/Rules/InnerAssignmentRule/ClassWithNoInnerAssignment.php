<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithNoInnerAssignment
{
    public function compute(int $a): int
    {
        $result = $a * 2;
        $result = $result + 1;

        return $result;
    }
}
