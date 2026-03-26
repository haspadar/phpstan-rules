<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects logic in class constructors that goes beyond property initialization.
 * A constructor must only contain assignments to $this->property and calls to
 * parent::__construct(). Any other statements — function calls, method calls,
 * conditionals, loops — are reported as errors. Constructor property promotion
 * parameters produce no body statements and are always permitted.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ConstructorInitializationRule implements Rule
{
    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if ($node->name->toString() !== '__construct') {
            return [];
        }

        if ($node->stmts === null) {
            return [];
        }

        $errors = [];

        foreach ($node->stmts as $stmt) {
            if ($this->isAllowedStatement($stmt)) {
                continue;
            }

            $reflection = $scope->getClassReflection();
            $className = $reflection !== null ? $reflection->getName() : 'anonymous'; // @codeCoverageIgnore

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Constructor of %s must only initialize properties. Found: %s.',
                    $className,
                    $stmt->getType(),
                ),
            )
                ->identifier('haspadar.constructorInit')
                ->line($stmt->getLine())
                ->build();
        }

        return $errors;
    }

    /**
     * @param Node\Stmt $stmt
     */
    private function isAllowedStatement(Node\Stmt $stmt): bool
    {
        if (!$stmt instanceof Expression) {
            return false;
        }

        return $this->isThisPropertyAssignment($stmt) || $this->isParentConstructorCall($stmt);
    }

    /**
     * @param Expression $stmt
     */
    private function isThisPropertyAssignment(Expression $stmt): bool
    {
        if (!$stmt->expr instanceof Assign) {
            return false;
        }

        $assign = $stmt->expr;

        if (!$assign->var instanceof PropertyFetch) {
            return false;
        }

        $propertyFetch = $assign->var;

        if (!$propertyFetch->var instanceof Variable || $propertyFetch->var->name !== 'this') {
            return false;
        }

        return $this->isAllowedValue($assign->expr);
    }

    /**
     * @param Expr $expr
     */
    private function isAllowedValue(Expr $expr): bool
    {
        return $expr instanceof Variable
            || $expr instanceof Expr\New_
            || $expr instanceof Expr\Array_
            || $expr instanceof Expr\ConstFetch
            || $expr instanceof Expr\ClassConstFetch
            || $expr instanceof Node\Scalar\String_
            || $expr instanceof Node\Scalar\Int_
            || $expr instanceof Node\Scalar\Float_;
    }

    /**
     * @param Expression $stmt
     */
    private function isParentConstructorCall(Expression $stmt): bool
    {
        if (!$stmt->expr instanceof StaticCall) {
            return false;
        }

        $call = $stmt->expr;

        if (!$call->class instanceof Name) {
            return false;
        }

        if ($call->class->toString() !== 'parent') {
            return false;
        }

        return $call->name instanceof Node\Identifier
            && $call->name->toString() === '__construct';
    }
}
