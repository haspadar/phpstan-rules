<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects reassignment of method or constructor parameters.
 * A parameter must not be reassigned within the method body via simple
 * assignment, compound assignment (+=, .=, etc.), assignment by reference,
 * or increment/decrement operators. Assignments inside closures, arrow
 * functions, nested functions, and anonymous classes are excluded as they
 * introduce a new variable scope.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NoParameterReassignmentRule implements Rule
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
        $paramNames = $this->parameterNames($node);

        if ($paramNames === []) {
            return [];
        }

        $errors = [];
        $className = $scope->getClassReflection()?->getName() ?? 'anonymous';
        $methodName = $node->name->toString();

        foreach ($this->findWriteExpressions($node) as $expr) {
            if (!$expr->var instanceof Variable) {
                continue;
            }

            $varName = $expr->var->name;

            if (!is_string($varName) || !in_array($varName, $paramNames, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Parameter $%s must not be reassigned in method %s() of %s.',
                    $varName,
                    $methodName,
                    $className,
                ),
            )
                ->identifier('haspadar.noParameterReassignment')
                ->line($expr->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Finds all write-expression nodes within the method body, excluding those
     * inside closures, arrow functions, nested functions, and anonymous classes
     * which introduce a new variable scope.
     *
     * @param ClassMethod $node
     *
     * @return list<Assign|AssignOp|AssignRef|PreInc|PostInc|PreDec|PostDec>
     */
    private function findWriteExpressions(ClassMethod $node): array
    {
        /** @var list<Assign|AssignOp|AssignRef|PreInc|PostInc|PreDec|PostDec> */
        return (new NodeFinder())->find(
            $node->stmts ?? [],
            static fn(Node $n): bool => ($n instanceof Assign
                || $n instanceof AssignOp
                || $n instanceof AssignRef
                || $n instanceof PreInc
                || $n instanceof PostInc
                || $n instanceof PreDec
                || $n instanceof PostDec)
                && !self::isInsideScopeBoundary($n, $node),
        );
    }

    /**
     * Returns true if the node is nested inside a closure, arrow function,
     * nested function declaration, or anonymous class within the given method,
     * meaning it belongs to a different variable scope.
     *
     * @param Node $target
     * @param ClassMethod $method
     */
    private static function isInsideScopeBoundary(Node $target, ClassMethod $method): bool
    {
        $parents = (new NodeFinder())->find(
            $method->stmts ?? [],
            static fn(Node $n): bool => ($n instanceof Closure
                || $n instanceof ArrowFunction
                || $n instanceof Function_
                || $n instanceof Class_)
                && (new NodeFinder())->findFirst([$n], static fn(Node $inner): bool => $inner === $target) !== null,
        );

        return $parents !== [];
    }

    /**
     * @param ClassMethod $node
     *
     * @return list<string>
     */
    private function parameterNames(ClassMethod $node): array
    {
        $names = [];

        foreach ($node->params as $param) {
            if ($param->var instanceof Variable && is_string($param->var->name)) {
                $names[] = $param->var->name;
            }
        }

        return $names;
    }
}
