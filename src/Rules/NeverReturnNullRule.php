<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports nullable return types and explicit `return null` in methods and standalone functions.
 *
 * Detects three patterns:
 * - `?Type` (NullableType)
 * - `Type|null` (UnionType containing null)
 * - `return null;` (explicit null return statement)
 *
 * Closures and arrow functions are excluded because they commonly
 * use nullable returns for optional callbacks.
 *
 * @implements Rule<Stmt>
 */
final readonly class NeverReturnNullRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Stmt::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @param Stmt $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof ClassMethod && !$node instanceof Function_) {
            return [];
        }

        $errors = [];
        $label = $this->functionLabel($node, $scope);

        if ($this->hasNullableReturnType($node)) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('%s must not have a nullable return type.', $label),
            )
                ->identifier('haspadar.noNullReturn')
                ->line($node->getStartLine())
                ->build();
        }

        foreach ($this->findReturnNullStatements($node) as $return) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('%s must not return null.', $label),
            )
                ->identifier('haspadar.noNullReturn')
                ->line($return->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true when the function declares a nullable return type.
     */
    private function hasNullableReturnType(FunctionLike $node): bool
    {
        if ($node->getReturnType() instanceof NullableType) {
            return true;
        }

        $returnType = $node->getReturnType();

        if (!$returnType instanceof UnionType) {
            return false;
        }

        foreach ($returnType->types as $type) {
            if ($type instanceof Identifier && $type->toLowerString() === 'null') {
                return true;
            }
        }

        return false;
    }

    /**
     * Finds all `return null;` statements in the function body, excluding nested closures and arrow functions.
     *
     * @return list<Return_>
     */
    private function findReturnNullStatements(FunctionLike $node): array
    {
        $stmts = $node->getStmts();

        if ($stmts === null) {
            return [];
        }

        $finder = new NodeFinder();
        $results = [];

        /** @var list<Return_> $returns */
        $returns = $finder->find(
            $stmts,
            static fn(Node $n): bool => $n instanceof Return_
                && $n->expr instanceof ConstFetch
                && $n->expr->name->toLowerString() === 'null',
        );

        foreach ($returns as $return) {
            if (!$this->isInsideNestedFunction($return, array_values($stmts))) {
                $results[] = $return;
            }
        }

        return $results;
    }

    /**
     * Checks whether a node is inside a nested closure or arrow function.
     *
     * @param list<Stmt> $stmts
     */
    private function isInsideNestedFunction(Return_ $return, array $stmts): bool
    {
        $finder = new NodeFinder();

        /** @var list<Closure|ArrowFunction> $nestedFunctions */
        $nestedFunctions = $finder->find(
            $stmts,
            static fn(Node $n): bool => $n instanceof Closure || $n instanceof ArrowFunction,
        );

        $returnLine = $return->getStartLine();
        $returnEndLine = $return->getEndLine();

        foreach ($nestedFunctions as $nested) {
            if ($returnLine >= $nested->getStartLine() && $returnEndLine <= $nested->getEndLine()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns a human-readable label for the enclosing function or method.
     *
     * @param ClassMethod|Function_ $node
     */
    private function functionLabel(FunctionLike $node, Scope $scope): string
    {
        if ($node instanceof ClassMethod) {
            $classReflection = $scope->getClassReflection();
            assert($classReflection !== null);

            return sprintf('Method %s::%s()', $classReflection->getName(), $node->name->toString());
        }

        return sprintf('Function %s()', $node->namespacedName ?? $node->name->toString());
    }
}
