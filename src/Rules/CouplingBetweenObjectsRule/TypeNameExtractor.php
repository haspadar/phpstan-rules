<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;

use PhpParser\Node;
use PhpParser\Node\Name;

/**
 * Extracts class/interface/trait names from type nodes,
 * handling nullable, union, and intersection types recursively
 */
final class TypeNameExtractor
{
    /**
     * Returns all type names referenced in the given type node
     *
     * @return list<string>
     */
    public function extract(Node $typeNode): array
    {
        if ($typeNode instanceof Name) {
            return [$typeNode->toString()];
        }

        if ($typeNode instanceof Node\NullableType) {
            return $this->extract($typeNode->type);
        }

        if ($typeNode instanceof Node\UnionType || $typeNode instanceof Node\IntersectionType) {
            $result = [];

            foreach ($typeNode->types as $type) {
                $result = array_merge($result, $this->extract($type));
            }

            return $result;
        }

        return [];
    }
}
