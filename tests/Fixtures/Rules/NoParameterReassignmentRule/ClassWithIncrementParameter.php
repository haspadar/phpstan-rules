<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithIncrementParameter
{
    public function process(int $count): int
    {
        ++$count;

        return $count;
    }
}
