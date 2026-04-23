<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class ClassWithCoalesceAssignment
{
    /**
     * @param array<string, int|null> $input
     */
    public function normalise(array $input): int
    {
        $input['first'] ??= null;
        return $input['first'] ?? 0;
    }
}
