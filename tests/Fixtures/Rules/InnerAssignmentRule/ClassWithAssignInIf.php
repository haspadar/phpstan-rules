<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithAssignInIf
{
    public function check(mixed $value): bool
    {
        if ($result = $this->compute($value)) {
            return $result;
        }

        return false;
    }

    private function compute(mixed $value): bool
    {
        return $value !== null;
    }
}
