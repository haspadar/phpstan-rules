<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\PropertyItem;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports nullable types in class property declarations.
 *
 * Detects two patterns on the property native type:
 * - `?Type` (NullableType)
 * - `Type|null` / `null|Type` (UnionType containing null)
 *
 * Promoted constructor properties are out of scope — handled by NeverAcceptNullArgumentsRule.
 *
 * @implements Rule<Property>
 */
final readonly class NoNullablePropertyRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Property::class;
    }

    /**
     * Analyses the property node and returns one error per nullable declaration.
     *
     * @param Property $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->hasNullableType($node)) {
            return [];
        }

        $classLabel = $this->classLabel($scope);
        $errors = [];

        foreach ($node->props as $prop) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Property $%s in %s must not be nullable.',
                    $this->propertyName($prop),
                    $classLabel,
                ),
            )
                ->identifier('haspadar.noNullableProperty')
                ->line($prop->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true when the property type is nullable — standalone `null`, `?Type`, or a union containing null.
     */
    private function hasNullableType(Property $node): bool
    {
        if ($node->type instanceof Identifier && $node->type->toLowerString() === 'null') {
            return true;
        }

        if ($node->type instanceof NullableType) {
            return true;
        }

        if ($node->type instanceof UnionType) {
            foreach ($node->type->types as $type) {
                if ($type instanceof Identifier && $type->toLowerString() === 'null') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Extracts the property name as a string.
     */
    private function propertyName(PropertyItem $prop): string
    {
        return $prop->name->toString();
    }

    /**
     * Returns a human-readable label for the enclosing class.
     */
    private function classLabel(Scope $scope): string
    {
        $classReflection = $scope->getClassReflection();

        if ($classReflection === null) {
            return 'anonymous class';
        }

        return sprintf('class %s', $classReflection->getName());
    }
}
