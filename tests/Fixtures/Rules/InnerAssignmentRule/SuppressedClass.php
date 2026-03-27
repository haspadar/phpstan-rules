<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class SuppressedClass
{
    public function check(mixed $value): bool
    {
        /** @phpstan-ignore haspadar.innerAssignment */
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
