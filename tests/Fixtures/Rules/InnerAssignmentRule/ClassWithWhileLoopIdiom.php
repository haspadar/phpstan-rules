<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithWhileLoopIdiom
{
    /** @param resource $handle */
    public function readLines(mixed $handle): string
    {
        $output = '';

        while (($line = fgets($handle)) !== false) {
            $output .= $line;
        }

        return $output;
    }
}
