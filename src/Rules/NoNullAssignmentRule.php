<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports plain assignments of the `null` literal to variables, properties, and array elements.
 * Follows psalm-eo-rules `NoNullChecker`: `null` represents absence and breaks object integrity;
 * absence must be modelled explicitly through a Null Object, Optional, or a sensible default
 * value. Coalesce-assign `$x ??= null` is not flagged because it is represented by a different
 * AST node (`AssignOp\Coalesce`) and the rule subscribes only to `Assign`. Property defaults
 * `public ?Type $x = null` and nullable parameter declarations are declarations, not runtime
 * assignments, and fall under NoNullablePropertyRule and NeverAcceptNullArgumentsRule.
 *
 * @implements Rule<Assign>
 */
final readonly class NoNullAssignmentRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Assign::class;
    }

    /**
     * Reports an error when the right-hand side is the `null` literal.
     *
     * @psalm-param Assign $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->isNullLiteral($node->expr)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Assignment of null to %s is prohibited. Model absence explicitly (Null Object, Optional).',
                    $this->targetDescription($node->var),
                ),
            )
                ->identifier('haspadar.noNullAssignment')
                ->build(),
        ];
    }

    /**
     * Returns true when the expression is the constant `null` (case-insensitive).
     */
    private function isNullLiteral(Expr $expr): bool
    {
        return $expr instanceof ConstFetch
            && $expr->name->toLowerString() === 'null';
    }

    /**
     * Produces a short human-readable label for the assignment target used in the error message.
     */
    private function targetDescription(Expr $target): string
    {
        if ($target instanceof Variable && is_string($target->name)) {
            return sprintf('$%s', $target->name);
        }

        if ($target instanceof PropertyFetch && $target->name instanceof Identifier) {
            $property = $target->name->toString();

            if ($target->var instanceof Variable && is_string($target->var->name)) {
                return sprintf('$%s->%s', $target->var->name, $property);
            }

            return sprintf('property $%s', $property);
        }

        if ($target instanceof StaticPropertyFetch
            && $target->class instanceof Name
            && $target->name instanceof Identifier
        ) {
            return sprintf('%s::$%s', $target->class->toString(), $target->name->toString());
        }

        if ($target instanceof ArrayDimFetch) {
            return 'array element';
        }

        return 'target';
    }
}
