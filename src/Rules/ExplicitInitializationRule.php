<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports nullable typed properties explicitly initialized to null.
 *
 * A nullable property (`?Foo`, `Foo|null`) without an explicit default is still
 * nullable — PHP allows it to remain uninitialized until the constructor runs.
 * Repeating `= null` on a nullable property is therefore redundant: it does not
 * change observable behavior but adds visual noise.
 *
 * Only nullable types are flagged: non-nullable typed properties (`int`, `string`,
 * etc.) without a default are uninitialized, so any explicit default value there
 * carries real semantic meaning and must not be reported.
 *
 * Both `?T` (NullableType) and `T|null` / `null|T` (UnionType) syntaxes are
 * recognized. Anonymous classes are skipped; abstract classes are analyzed.
 *
 * @implements Rule<Class_>
 */
final readonly class ExplicitInitializationRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isAnonymous()) {
            return [];
        }

        $errors = [];

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Property) {
                $errors = [...$errors, ...$this->errorsForProperty($stmt)];
            }
        }

        return $errors;
    }

    /**
     * Returns errors for each nullable property initialized to null.
     *
     * @return list<IdentifierRuleError>
     */
    private function errorsForProperty(Property $property): array
    {
        if ($property->type === null) {
            return [];
        }

        if (!$this->isNullableType($property->type)) {
            return [];
        }

        $errors = [];

        foreach ($property->props as $prop) {
            if ($prop->default === null) {
                continue;
            }

            if (!$this->isNullLiteral($prop->default)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Property $%s is explicitly initialized to its default value.', $prop->name->toString()),
            )
                ->identifier('haspadar.explicitInit')
                ->line($prop->getLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the type is nullable: either `?T` or a union containing `null`.
     */
    private function isNullableType(Node\ComplexType|Node\Identifier|Node\Name $type): bool
    {
        if ($type instanceof NullableType) {
            return true;
        }

        if ($type instanceof Node\UnionType) {
            foreach ($type->types as $unionedType) {
                if ($unionedType instanceof Node\Identifier && $unionedType->toLowerString() === 'null') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if the expression is the null literal.
     */
    private function isNullLiteral(Node\Expr $expr): bool
    {
        return $expr instanceof ConstFetch && strtolower($expr->name->toString()) === 'null';
    }
}
