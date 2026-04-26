<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\MultipleVariableDeclarationsRule;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeFinder;
use PHPStan\Node\FileNode;

/**
 * Collects every list of statements that an AST file exposes as a child collection.
 *
 * The file-level node list is returned alongside the body of every node type
 * whose `stmts` property carries a statement collection: namespaces,
 * function-likes, branches of `if`/`else`/`elseif`, loop bodies, `switch`
 * cases and `try`/`catch`/`finally` blocks.
 */
final readonly class StatementListCollector
{
    /**
     * Returns every list of statements found inside the file.
     *
     * @param FileNode $node File-level AST node provided by PHPStan.
     * @return list<array<array-key, Stmt>>
     */
    public function collect(FileNode $node): array
    {
        $lists = [array_filter(
            $node->getNodes(),
            static fn(Node $n): bool => $n instanceof Stmt,
        )];

        $simpleHolders = [
            Function_::class,
            Closure::class,
            Namespace_::class,
            If_::class,
            ElseIf_::class,
            Else_::class,
            For_::class,
            Foreach_::class,
            While_::class,
            Do_::class,
            TryCatch::class,
            Catch_::class,
        ];

        foreach ($simpleHolders as $type) {
            foreach ($this->bodiesOf($node, $type) as $body) {
                $lists[] = $body;
            }
        }

        foreach ($this->classMethodBodies($node) as $body) {
            $lists[] = $body;
        }

        foreach ($this->finallyBodies($node) as $body) {
            $lists[] = $body;
        }

        foreach ($this->switchCaseBodies($node) as $body) {
            $lists[] = $body;
        }

        return $lists;
    }

    /**
     * Returns the `stmts` arrays of all nodes of the given type inside the file.
     *
     * @template T of Function_|Closure|Namespace_|If_|ElseIf_|Else_|For_|Foreach_|While_|Do_|TryCatch|Catch_
     * @param class-string<T> $type
     * @return list<array<array-key, Stmt>>
     */
    private function bodiesOf(FileNode $node, string $type): array
    {
        /** @var list<T> $matches */
        $matches = (new NodeFinder())->findInstanceOf($node->getNodes(), $type);
        $lists = [];

        foreach ($matches as $match) {
            $lists[] = $match->stmts;
        }

        return $lists;
    }

    /**
     * Returns the bodies of every concrete `ClassMethod`, skipping abstract methods.
     *
     * @return list<array<array-key, Stmt>>
     */
    private function classMethodBodies(FileNode $node): array
    {
        /** @var list<ClassMethod> $methods */
        $methods = (new NodeFinder())->findInstanceOf($node->getNodes(), ClassMethod::class);
        $lists = [];

        foreach ($methods as $method) {
            if ($method->stmts !== null) {
                $lists[] = $method->stmts;
            }
        }

        return $lists;
    }

    /**
     * Returns the bodies of every `finally` block attached to a `try` statement.
     *
     * @return list<array<array-key, Stmt>>
     */
    private function finallyBodies(FileNode $node): array
    {
        /** @var list<TryCatch> $tries */
        $tries = (new NodeFinder())->findInstanceOf($node->getNodes(), TryCatch::class);
        $lists = [];

        foreach ($tries as $try) {
            if ($try->finally !== null) {
                $lists[] = $try->finally->stmts;
            }
        }

        return $lists;
    }

    /**
     * Returns the bodies of every `case` inside every `switch` statement.
     *
     * @return list<array<array-key, Stmt>>
     */
    private function switchCaseBodies(FileNode $node): array
    {
        /** @var list<Switch_> $switches */
        $switches = (new NodeFinder())->findInstanceOf($node->getNodes(), Switch_::class);
        $lists = [];

        foreach ($switches as $switch) {
            foreach ($switch->cases as $case) {
                $lists[] = $case->stmts;
            }
        }

        return $lists;
    }
}
