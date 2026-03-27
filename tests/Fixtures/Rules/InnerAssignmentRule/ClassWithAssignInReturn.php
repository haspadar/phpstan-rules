<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithAssignInReturn
{
    private int $last = 0;

    public function next(int $value): int
    {
        return $this->last = $value;
    }
}
