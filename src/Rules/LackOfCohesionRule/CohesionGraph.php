<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\LackOfCohesionRule;

use PhpParser\Node\Stmt\ClassMethod;

/**
 * Counts connected components (LCOM4) in the cohesion graph of class methods.
 *
 * Vertices are all given methods; edges connect methods that either share a touched
 * property or call one another via `$this->method()`, `self::method()` or `static::method()`.
 * The number of connected components is the LCOM4 value of the class.
 */
final readonly class CohesionGraph
{
    /**
     * Returns the LCOM4 value for the given methods.
     *
     * @param list<ClassMethod> $methods
     */
    public function componentCount(array $methods): int
    {
        $touches = $this->touchesFor($methods);
        $adjacency = (new AdjacencyBuilder())->build($methods, $touches);

        return $this->countComponents($adjacency);
    }

    /**
     * Computes touches for the given methods.
     *
     * @param list<ClassMethod> $methods
     * @return list<array{properties: list<string>, calls: list<string>}>
     */
    private function touchesFor(array $methods): array
    {
        $collector = new MethodTouches();
        $result = [];

        foreach ($methods as $method) {
            $result[] = $collector->collect($method);
        }

        return $result;
    }

    /**
     * Counts connected components via iterative depth-first search.
     *
     * @param array<int, list<int>> $adjacency
     */
    private function countComponents(array $adjacency): int
    {
        $visited = [];
        $components = 0;

        foreach (array_keys($adjacency) as $start) {
            if (array_key_exists($start, $visited)) {
                continue;
            }

            $visited = $this->markReachable($adjacency, $start, $visited);
            $components++;
        }

        return $components;
    }

    /**
     * Marks every node reachable from `$start` as visited and returns the updated set.
     *
     * @param array<int, list<int>> $adjacency
     * @param array<int, true> $visited
     * @return array<int, true>
     */
    private function markReachable(array $adjacency, int $start, array $visited): array
    {
        $seen = $visited;
        $stack = [$start];

        while ($stack !== []) {
            $node = array_pop($stack);

            if (array_key_exists($node, $seen)) {
                continue;
            }

            $seen[$node] = true;

            foreach ($adjacency[$node] as $neighbour) {
                if (!array_key_exists($neighbour, $seen)) {
                    $stack[] = $neighbour;
                }
            }
        }

        return $seen;
    }
}
