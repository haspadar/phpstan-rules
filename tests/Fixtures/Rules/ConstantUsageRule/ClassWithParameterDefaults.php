<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithParameterDefaults
{
    public function scalarDefault(int $limit = 10): int
    {
        return $limit;
    }

    /**
     * Uses an array default with scalar values inside.
     *
     * @param list<int> $items
     * @return list<int>
     */
    public function arrayDefault(array $items = [1, 2, 3]): array
    {
        return $items;
    }
}
