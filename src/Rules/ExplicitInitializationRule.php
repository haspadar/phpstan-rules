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
 * Reports typed class properties explicitly initialized to their PHP default value.
 *
 * PHP already assigns default values to typed properties when no explicit default
 * is given: nullable types default to null. Repeating `= null`, `= 0`, `= false`,
 * `= 0.0`, or `= ''` is redundant — it adds noise without conveying intent.
 *
 * Checks every typed property declaration (`Property` node with a non-null type).
 * Untyped properties are skipped because an explicit initializer there carries
 * documentary value. Abstract and anonymous classes are skipped.
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
        if ($node->isAbstract() || $node->isAnonymous()) {
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
     * Returns errors for each property variable with a redundant default value.
     *
     * @return list<IdentifierRuleError>
     */
    private function errorsForProperty(Property $property): array
    {
        if ($property->type === null) {
            return [];
        }

        $type = $property->type;
        $errors = [];

        foreach ($property->props as $prop) {
            if ($prop->default === null) {
                continue;
            }

            if (!$this->isRedundantDefault($type, $prop->default)) {
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
     * Returns true if the given default value is the implicit PHP default for the type.
     */
    private function isRedundantDefault(
        Node\ComplexType|Node\Identifier|Node\Name $type,
        Node\Expr $default,
    ): bool {
        if ($type instanceof NullableType) {
            return $this->isNullLiteral($default);
        }

        $typeName = $type instanceof Node\Identifier
            ? strtolower($type->toString())
            : null;

        return match ($typeName) {
            'int' => $this->isIntZero($default),
            'float' => $this->isFloatZero($default),
            'bool' => $this->isFalseLiteral($default),
            'string' => $this->isEmptyString($default),
            default => false,
        };
    }

    /**
     * Returns true if the expression is the null literal.
     */
    private function isNullLiteral(Node\Expr $expr): bool
    {
        return $expr instanceof ConstFetch && strtolower($expr->name->toString()) === 'null';
    }

    /**
     * Returns true if the expression is the integer literal 0.
     */
    private function isIntZero(Node\Expr $expr): bool
    {
        return $expr instanceof Node\Scalar\Int_ && $expr->value === 0;
    }

    /**
     * Returns true if the expression is a float literal with value zero (0.0, 0.).
     */
    private function isFloatZero(Node\Expr $expr): bool
    {
        return $expr instanceof Node\Scalar\Float_ && $expr->value === (float) 0;
    }

    /**
     * Returns true if the expression is the false literal.
     */
    private function isFalseLiteral(Node\Expr $expr): bool
    {
        return $expr instanceof ConstFetch && strtolower($expr->name->toString()) === 'false';
    }

    /**
     * Returns true if the expression is an empty string literal.
     */
    private function isEmptyString(Node\Expr $expr): bool
    {
        return $expr instanceof Node\Scalar\String_ && $expr->value === '';
    }
}
