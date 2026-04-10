<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Static_;
use PhpParser\NodeFinder;

/**
 * Collects local variable names with line numbers from a method body.
 * Handles assignments, foreach, destructuring, and static variables.
 * Excludes variables inside nested scopes (closures, arrow functions,
 * anonymous classes, nested functions).
 */
final class VariableCollector
{
    /**
     * Returns all local variable name-line pairs from the method body.
     *
     * @return list<array{string, int}>
     */
    public function collect(ClassMethod $node): array
    {
        /** @var list<Node> $nodes */
        $nodes = (new NodeFinder())->find(
            $node->stmts ?? [],
            static fn(Node $n): bool => ($n instanceof Assign
                || $n instanceof AssignRef
                || $n instanceof Foreach_
                || $n instanceof Static_)
                && !self::isInsideScopeBoundary($n, $node),
        );

        $vars = [];

        foreach ($nodes as $found) {
            $vars = array_merge($vars, $this->variablesFromNode($found));
        }

        return $vars;
    }

    /**
     * Extracts name-line pairs from an AST node.
     *
     * @return list<array{string, int}>
     */
    private function variablesFromNode(Node $found): array
    {
        return match (true) {
            $found instanceof Assign, $found instanceof AssignRef => $this->variablesFromTarget($found->var),
            $found instanceof Foreach_ => $this->variablesFromForeach($found),
            $found instanceof Static_ => $this->variablesFromStatic($found),
            default => [],
        };
    }

    /**
     * Extracts variables from foreach key and value.
     *
     * @return list<array{string, int}>
     */
    private function variablesFromForeach(Foreach_ $node): array
    {
        $result = $node->keyVar !== null
            ? $this->variablesFromTarget($node->keyVar)
            : [];

        return array_merge($result, $this->variablesFromTarget($node->valueVar));
    }

    /**
     * Extracts variables from static variable declarations.
     *
     * @return list<array{string, int}>
     */
    private function variablesFromStatic(Static_ $node): array
    {
        $result = [];

        foreach ($node->vars as $staticVar) {
            $result = array_merge($result, $this->toNameLine($staticVar->var));
        }

        return $result;
    }

    /**
     * Extracts variables from an assignment target, handling destructuring.
     *
     * @return list<array{string, int}>
     */
    private function variablesFromTarget(Node $target): array
    {
        if ($target instanceof Variable) {
            return $this->toNameLine($target);
        }

        if (!($target instanceof Node\Expr\Array_) && !($target instanceof List_)) {
            return [];
        }

        $result = [];

        foreach ($target->items as $item) {
            if ($item !== null) {
                $result = array_merge($result, $this->variablesFromTarget($item->value));
            }
        }

        return $result;
    }

    /**
     * Returns a single-element list with variable name and line, or empty.
     *
     * @return list<array{string, int}>
     */
    private function toNameLine(Node\Expr $expr): array
    {
        return $expr instanceof Variable && is_string($expr->name) && $expr->name !== 'this'
            ? [[$expr->name, $expr->getStartLine()]]
            : [];
    }

    /**
     * Returns true if the node is nested inside a scope boundary.
     */
    private static function isInsideScopeBoundary(Node $target, ClassMethod $method): bool
    {
        return (new NodeFinder())->find(
            $method->stmts ?? [],
            static fn(Node $n): bool => ($n instanceof Closure
                || $n instanceof ArrowFunction
                || $n instanceof Function_
                || $n instanceof Class_)
                && (new NodeFinder())->findFirst([$n], static fn(Node $inner): bool => $inner === $target) !== null,
        ) !== [];
    }
}
