<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithDoWhileLoopIdiom
{
    /** @param resource $handle */
    public function readLines(mixed $handle): string
    {
        $output = '';
        $line = '';

        do {
            $output .= $line;
        } while (($line = fgets($handle)) !== false);

        return $output;
    }
}
