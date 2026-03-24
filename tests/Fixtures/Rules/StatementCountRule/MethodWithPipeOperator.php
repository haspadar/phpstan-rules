<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule;

final class MethodWithPipeOperator
{
    /** @param list<int> $data */
    public function run(array $data): mixed
    {
        $result = $data
            |> (fn(array $x): array => array_filter($x, fn(int $n): bool => $n > 0))
            |> (fn(array $x): array => array_map(fn(int $n): int => $n * 2, $x));
        return $result;
    }
}
