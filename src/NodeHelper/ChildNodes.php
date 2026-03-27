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
     * Returns all direct child Node instances of the given node
     *
     * @return list<Node>
     */
    public static function of(Node $node): array
    {
        $children = [];

        foreach ($node->getSubNodeNames() as $name) {
            /** @var mixed $child -- dynamic access is intentional: PhpParser sub-node API */
            // @phpstan-ignore property.dynamicName
            $child = $node->$name;

            if ($child instanceof Node) {
                $children[] = $child;
            } elseif (is_array($child)) {
                /** @psalm-suppress MixedAssignment */
                foreach ($child as $item) {
                    if ($item instanceof Node) {
                        $children[] = $item;
                    }
                }
            }
        }

        return $children;
    }
}
