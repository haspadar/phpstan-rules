<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoParameterReassignmentRule;

final class ClassWithMultipleParameterReassignments
{
    public function format(string $first, string $last): string
    {
        $first = strtolower($first);
        $last = strtoupper($last);

        return $first . ' ' . $last;
    }
}
