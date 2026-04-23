<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithCoalesceAssignment
{
    /**
     * @param array<string, int> $input
     */
    public function pickFirst(array $input): int
    {
        $result = $input['first'] ?? 0;
        return $result;
    }
}
