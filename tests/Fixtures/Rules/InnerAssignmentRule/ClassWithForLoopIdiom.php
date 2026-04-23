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

    public function classicInitAndUpdate(): int
    {
        $total = 0;

        for ($i = 0; $i < 10; $i++) {
            $total += $i;
        }

        return $total;
    }

    public function multipleInitExpressions(): int
    {
        $sum = 0;

        for ($i = 0, $j = 10; $i < $j; $i++) {
            $sum += $i;
        }

        return $sum;
    }

    public function assignOpInUpdate(): int
    {
        $sum = 0;

        for ($i = 10; $i > 0; $i -= 1) {
            $sum += $i;
        }

        return $sum;
    }

    /** @param resource $handle */
    public function combinedInitCondUpdate(mixed $handle): string
    {
        $output = '';

        for ($i = 0; ($line = fgets($handle)) !== false; $i += 1) {
            $output .= $i . $line;
        }

        return $output;
    }
}
