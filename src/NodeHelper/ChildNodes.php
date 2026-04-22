<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\NodeHelper;

use PhpParser\Node;

/**
 * Collects direct child nodes of a PhpParser Node by iterating over its sub-node names.
 * Uses PhpParser's getSubNodeNames() API — the only way to enumerate child nodes for
 * arbitrary node types. Dynamic property access is intentional and unavoidable here.
 */
final class ChildNodes
{
    /**
     * Returns all direct child Node instances of the given node.
     *
     * @param Node $node Parent node whose direct children are returned.
     * @return list<Node>
     */
    public static function of(Node $node): array
    {
        $children = [];

        foreach ($node->getSubNodeNames() as $name) {
            /** @var mixed $child -- dynamic access is intentional: PhpParser sub-node API */
            // @phpstan-ignore property.dynamicName
            $child = $node->$name;

            foreach (self::extractNodes($child) as $extracted) {
                $children[] = $extracted;
            }
        }

        return $children;
    }

    /**
     * Extracts Node instances from a value that may be a Node, an array of Nodes, or neither.
     *
     * @return list<Node>
     */
    private static function extractNodes(mixed $value): array
    {
        if ($value instanceof Node) {
            return [$value];
        }

        if (!is_array($value)) {
            return [];
        }

        $nodes = [];

        /** @psalm-suppress MixedAssignment */
        foreach ($value as $item) {
            if ($item instanceof Node) {
                $nodes[] = $item;
            }
        }

        return $nodes;
    }
}
