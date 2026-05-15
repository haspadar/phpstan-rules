<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that PHPDoc tags do not use long built-in type aliases.
 * Disallows `integer`, `boolean`, `double`, and `real` (and any non-PascalCase variant such as
 * `INTEGER`) in favour of their canonical short forms (`int`, `bool`, `float`).
 * PascalCase names like `Integer` or `Boolean` are treated as user-defined classes and allowed.
 * Covers @param, @return, @throws in methods and @var on properties.
 * Types nested inside union and intersection types are checked recursively.
 *
 * @implements Rule<Node>
 */
final readonly class ProhibitLongTypeAliasRule implements Rule
{
    private const array ALIAS_MAP = [
        'integer' => 'int',
        'boolean' => 'bool',
        'double' => 'float',
        'real' => 'float',
    ];

    private PhpDocDescriptionChecker $checker;

    /**
     * Constructs the rule and initialises the shared PHPDoc parser.
     *
     * @throws ShouldNotHappenException
     */
    public function __construct()
    {
        $this->checker = new PhpDocDescriptionChecker();
    }

    #[Override]
    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * Analyses the PHPDoc of a class method or property for long type aliases.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof ClassMethod && !$node instanceof Property) {
            return [];
        }

        $docComment = $node->getDocComment();

        if ($scope->getClassReflection() === null || $docComment === null) {
            return [];
        }

        $phpDocNode = $this->checker->parse($docComment->getText());
        $errors = [];

        foreach ($phpDocNode->getTags() as $tag) {
            $type = $this->extractType($tag->value);

            if ($type === null) {
                continue;
            }

            foreach ($this->collectAliases($type) as $alias) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'PHPDoc contains long type alias "%s", use "%s" instead.',
                        $alias,
                        self::ALIAS_MAP[strtolower($alias)],
                    ),
                )
                    ->identifier('haspadar.prohibitLongTypeAlias')
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * Extracts the TypeNode from a tag value node that carries a `type` property, or null otherwise.
     *
     * @param object $tagValue Tag value node from the parsed PHPDoc.
     */
    private function extractType(object $tagValue): ?TypeNode
    {
        if (property_exists($tagValue, 'type') && $tagValue->type instanceof TypeNode) {
            return $tagValue->type;
        }

        return null;
    }

    /**
     * Recursively collects long alias names found anywhere inside the type tree.
     *
     * @return list<string>
     */
    private function collectAliases(TypeNode $type): array
    {
        if ($type instanceof IdentifierTypeNode) {
            $lower = strtolower($type->name);

            if (array_key_exists($lower, self::ALIAS_MAP) && $type->name !== ucfirst($lower)) {
                return [$type->name];
            }

            return [];
        }

        if ($type instanceof UnionTypeNode || $type instanceof IntersectionTypeNode) {
            $found = [];

            foreach ($type->types as $child) {
                foreach ($this->collectAliases($child) as $alias) {
                    $found[] = $alias;
                }
            }

            return $found;
        }

        if ($type instanceof NullableTypeNode) {
            return $this->collectAliases($type->type);
        }

        return [];
    }
}
