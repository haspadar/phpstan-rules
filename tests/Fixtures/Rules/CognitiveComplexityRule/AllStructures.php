<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CognitiveComplexityRule;

final class AllStructures
{
    /**
     * Covers switch, try/catch/finally, while, for, foreach, ternary, coalesce, break N, continue N.
     *
     * @param list<int> $items
     */
    public function allStructures(bool $flag, array $items, mixed $value): int
    {
        $result = 0;

        switch ($flag) {    // +1 (depth 0)
            case true:
                $result += 1;
                break;
        }

        try {
            $result += 1;
        } catch (\Exception $e) {   // +1 (depth 0)
            $result += 2;
        } finally {                  // +1 (depth 0)
            $result += 3;
        }

        while ($result > 0) {   // +1 (depth 0)
            $result--;
        }

        for ($i = 0; $i < 3; $i++) {   // +1 (depth 0)
            $result++;
        }

        foreach ($items as $item) {   // +1 (depth 0)
            $result += $item;
        }

        $result = $flag ? 1 : 0;   // +1 (depth 0) ternary

        $result = $value ?? 0;   // +1 (depth 0) coalesce

        outer: for ($i = 0; $i < 3; $i++) {   // +1 (depth 0)
            for ($j = 0; $j < 3; $j++) {       // +1 +1 (depth 1)
                if ($i === $j) {               // +1 +2 (depth 2)
                    continue 2;                // +1 labeled continue
                }
            }
        }

        return $result;
        // total = 1+1+1+1+1+1+1+1+1+1+1+1+1 = 13
    }
}
