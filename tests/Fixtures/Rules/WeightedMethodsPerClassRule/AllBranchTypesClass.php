<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\WeightedMethodsPerClassRule;

final class AllBranchTypesClass
{
    /** @param list<int> $items */
    public function controlFlow(int $val, array $items): int
    {
        $result = 0;

        if ($val > 0) {
            $result++;
        } elseif ($val < 0) {
            $result--;
        }

        while ($result < 10) {
            $result++;
        }

        do {
            $result++;
        } while ($result < 20);

        for ($idx = 0; $idx < $val; $idx++) {
            $result += $idx;
        }

        foreach ($items as $item) {
            $result += $item;
        }

        try {
            $result += intdiv($val, $result);
        } catch (\DivisionByZeroError $exc) {
            $result = strlen($exc->getMessage());
        }

        switch ($val) {
            case 1:
                $result = 10;
                break;
            case 2:
                $result = 20;
                break;
            default:
                $result = 30;
        }

        return $result;
    }

    public function logicalAndConditional(int $val, bool $flag): int
    {
        if ($val > 0 && $flag) {
            return 1;
        }

        if ($val < 0 || $flag) {
            return 2;
        }

        if ($val > 0 and $flag) {
            return 3;
        }

        if ($val < 0 or $flag) {
            return 4;
        }

        $ternary = $flag ? $val : 0;

        $coalesce = $ternary ?? 0;

        return match ($val) {
            1 => 10,
            2 => 20,
            default => $coalesce,
        };
    }
}
