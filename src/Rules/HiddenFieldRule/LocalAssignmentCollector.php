<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\HiddenFieldRule;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeFinder;

/**
 * Collects `$var = ...` assignments from a method body, excluding nested scopes.
 *
 * Nested scopes here mean closures, arrow functions, nested function declarations,
 * and anonymous classes — their locals belong to a different scope and must not
 * count as shadows of the outer class properties.
 */
final readonly class LocalAssignmentCollector
{
    /**
     * Returns distinct [varName, line] pairs for top-level assignments in the method body.
     *
     * @param ClassMethod $node Method AST node whose body is scanned for top-level `$var = ...` assignments
     * @return list<array{0: string, 1: int}>
     */
    public function collect(ClassMethod $node): array
    {
        if ($node->stmts === null) {
            return [];
        }

        $finder = new NodeFinder();
        $assignments = $finder->find($node->stmts, static fn(Node $inner): bool => $inner instanceof Assign);

        $seen = [];
        $result = [];

        foreach ($assignments as $assign) {
            if (!$assign instanceof Assign) {
                continue;
            }

            if (!$assign->var instanceof Variable || !is_string($assign->var->name)) {
                continue;
            }

            if ($this->isInsideNestedScope($assign, $node)) {
                continue;
            }

            $varName = $assign->var->name;

            if (array_key_exists($varName, $seen)) {
                continue;
            }

            $seen[$varName] = true;
            $result[] = [$varName, $assign->getStartLine()];
        }

        return $result;
    }

    /**
     * Returns true if the assignment is enclosed by a closure, arrow function, nested function, or anonymous class within the method.
     */
    private function isInsideNestedScope(Assign $assign, ClassMethod $method): bool
    {
        $finder = new NodeFinder();
        $boundaries = $finder->find(
            $method->stmts ?? [],
            static fn(Node $outer): bool => ($outer instanceof Closure
                || $outer instanceof ArrowFunction
                || $outer instanceof Function_
                || $outer instanceof Class_)
                && (new NodeFinder())->findFirst([$outer], static fn(Node $inner): bool => $inner === $assign) !== null,
        );

        return $boundaries !== [];
    }
}
