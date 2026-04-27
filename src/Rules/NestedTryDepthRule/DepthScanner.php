<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\NestedTryDepthRule;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PHPStan\Rules\IdentifierRuleError;

/**
 * Drives a `DepthVisitor` over a class method body and returns its errors.
 *
 * The scanner exists so that the rule can stay free of mutable state: it
 * builds a fresh visitor per invocation, runs the traversal, and hands the
 * errors back to the caller.
 */
final readonly class DepthScanner
{
    /**
     * Constructs the scanner for a single rule invocation.
     *
     * @param int $maxDepth Maximum allowed nesting depth
     * @param string $className FQCN of the surrounding class for the error message
     * @param string $methodName Method name for the error message
     */
    public function __construct(
        private int $maxDepth,
        private string $className,
        private string $methodName,
    ) {}

    /**
     * Scans the given class method and returns errors past the depth limit.
     *
     * @param ClassMethod $method Method whose body is analysed
     * @return list<IdentifierRuleError>
     */
    public function scan(ClassMethod $method): array
    {
        if ($method->stmts === null) {
            return [];
        }

        $visitor = new DepthVisitor($this->maxDepth, $this->className, $this->methodName);
        $traverser = new NodeTraverser($visitor);
        $traverser->traverse($method->stmts);

        return $visitor->errors();
    }
}
