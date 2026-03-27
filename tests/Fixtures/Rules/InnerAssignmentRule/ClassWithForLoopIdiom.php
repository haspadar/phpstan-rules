<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithForLoopIdiom
{
    /** @param resource $handle */
    public function readLines(mixed $handle): string
    {
        $output = '';

        for (; ($line = fgets($handle)) !== false; ) {
            $output .= $line;
        }

        return $output;
    }
}
