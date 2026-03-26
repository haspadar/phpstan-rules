<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects reassignment of method or constructor parameters.
 * A parameter must not be assigned a new value within the method body.
 * Assignments inside closures and arrow functions are excluded as they
 * introduce a new scope. Use a local variable instead to preserve the
 * original parameter value and make the transformation explicit.
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

        foreach ($this->findAssignments($node) as $assign) {
            if (!$assign->var instanceof Variable) {
                continue;
            }

            $varName = $assign->var->name;

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
                ->line($assign->getLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Finds all Assign nodes within the method body, excluding those inside
     * closures and arrow functions which introduce a new variable scope.
     *
     * @param ClassMethod $node
     *
     * @return list<Assign>
     */
    private function findAssignments(ClassMethod $node): array
    {
        /** @var list<Assign> $assigns */
        $assigns = (new NodeFinder())->find(
            $node->stmts ?? [],
            static fn(Node $n): bool => $n instanceof Assign
                && !self::isInsideScopeBoundary($n, $node),
        );

        return $assigns;
    }

    /**
     * Returns true if the node is nested inside a closure or arrow function
     * within the given method, meaning it belongs to a different variable scope.
     *
     * @param Node $target
     * @param ClassMethod $method
     */
    private static function isInsideScopeBoundary(Node $target, ClassMethod $method): bool
    {
        $parents = (new NodeFinder())->find(
            $method->stmts ?? [],
            static fn(Node $n): bool => ($n instanceof Closure || $n instanceof ArrowFunction)
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
