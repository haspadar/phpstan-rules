<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;

use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;

/**
 * Collects type names referenced in a method body: `new` expressions, static calls, and `catch` type hints.
 */
final class MethodBodyTypeCollector
{
    private readonly NodeFinder $finder;

    /** Initializes the shared NodeFinder instance. */
    public function __construct()
    {
        $this->finder = new NodeFinder();
    }

    /**
     * Returns all type names found in the method body statements.
     *
     * @param ClassMethod $method Method whose body statements are inspected.
     * @return list<string>
     */
    public function collect(ClassMethod $method): array
    {
        if ($method->stmts === null) {
            return [];
        }

        $names = [];

        /** @var list<New_> $newNodes */
        $newNodes = $this->finder->findInstanceOf($method->stmts, New_::class);

        foreach ($newNodes as $new) {
            if ($new->class instanceof Name) {
                $names[] = $new->class->toString();
            }
        }

        /** @var list<StaticCall> $staticCalls */
        $staticCalls = $this->finder->findInstanceOf($method->stmts, StaticCall::class);

        foreach ($staticCalls as $call) {
            if ($call->class instanceof Name) {
                $names[] = $call->class->toString();
            }
        }

        /** @var list<Catch_> $catches */
        $catches = $this->finder->findInstanceOf($method->stmts, Catch_::class);

        foreach ($catches as $catch) {
            foreach ($catch->types as $type) {
                $names[] = $type->toString();
            }
        }

        return $names;
    }
}
