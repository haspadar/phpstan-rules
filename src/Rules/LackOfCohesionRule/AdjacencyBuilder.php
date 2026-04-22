<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\LackOfCohesionRule;

use PhpParser\Node\Stmt\ClassMethod;

/**
 * Builds the undirected adjacency list of the method cohesion graph.
 *
 * Two methods get an edge when one calls the other via `$this->method()`, or when they
 * share at least one touched property (instance or static).
 */
final readonly class AdjacencyBuilder
{
    private const int MIN_PAIR_SIZE = 2;

    /**
     * Builds the adjacency list: each index maps to the list of connected indices.
     *
     * @param list<ClassMethod> $methods Methods that form the nodes of the graph.
     * @param list<array{properties: list<string>, calls: list<string>}> $touches Property and call references collected per method, indexed the same as $methods.
     * @return array<int, list<int>>
     */
    public function build(array $methods, array $touches): array
    {
        $count = count($methods);
        $methodIndex = $this->methodIndex($methods);

        $edges = [
            ...$this->callEdges($touches, $methodIndex),
            ...$this->propertyEdges($touches, $count),
        ];

        return $this->adjacencyFromEdges($count, $edges);
    }

    /**
     * Returns a map from lowercased method name to its index in `$methods`.
     *
     * PHP method names are case-insensitive, so keys are lowercased to match
     * callee names collected by `MethodTouches`.
     *
     * @param list<ClassMethod> $methods
     * @return array<string, int>
     */
    private function methodIndex(array $methods): array
    {
        $index = [];

        foreach ($methods as $i => $method) {
            $index[strtolower($method->name->toString())] = $i;
        }

        return $index;
    }

    /**
     * Returns edges connecting methods that call each other via `$this->method()`.
     *
     * @param list<array{properties: list<string>, calls: list<string>}> $touches
     * @param array<string, int> $methodIndex
     * @return list<array{0: int, 1: int}>
     */
    private function callEdges(array $touches, array $methodIndex): array
    {
        $edges = [];

        foreach ($touches as $i => $data) {
            foreach ($data['calls'] as $callee) {
                $target = $methodIndex[$callee] ?? null;

                if ($target !== null && $target !== $i) {
                    $edges[] = [$i, $target];
                }
            }
        }

        return $edges;
    }

    /**
     * Returns edges connecting methods that share at least one touched property.
     *
     * @param list<array{properties: list<string>, calls: list<string>}> $touches
     * @return list<array{0: int, 1: int}>
     */
    private function propertyEdges(array $touches, int $count): array
    {
        if ($count < self::MIN_PAIR_SIZE) {
            return [];
        }

        $edges = [];
        $lastIndex = $count - 1;

        foreach (range(0, $lastIndex - 1) as $i) {
            foreach (range($i + 1, $lastIndex) as $j) {
                if (array_intersect($touches[$i]['properties'], $touches[$j]['properties']) !== []) {
                    $edges[] = [$i, $j];
                }
            }
        }

        return $edges;
    }

    /**
     * Builds the symmetric adjacency map from a list of undirected edges.
     *
     * @param list<array{0: int, 1: int}> $edges
     * @return array<int, list<int>>
     */
    private function adjacencyFromEdges(int $count, array $edges): array
    {
        if ($count === 0) {
            return [];
        }

        $adjacency = [];

        foreach (range(0, $count - 1) as $i) {
            $adjacency[$i] = [];
        }

        foreach ($edges as [$from, $target]) {
            $adjacency[$from][] = $target;
            $adjacency[$target][] = $from;
        }

        return $adjacency;
    }
}
