<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InnerAssignmentRule;

final class ClassWithAssignInForBody
{
    /** @param resource $handle */
    public function readLines(mixed $handle): string
    {
        $output = '';

        for ($i = 0; $i < 10; $i++) {
            if (($line = fgets($handle)) !== false) {
                $output .= $line;
            }
        }

        return $output;
    }
}
