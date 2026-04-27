<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\NestedTryDepthRule\DepthScanner;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;

/**
 * Reports a class method whose nested `try` depth exceeds the configured limit.
 *
 * Depth is counted relative to the method body: an outermost `try` has depth 0,
 * a `try` directly inside another `try` (or its `catch`/`finally` branches) has
 * depth 1, and so on. Sibling `catch` and `finally` clauses do not increase the
 * depth — they are branches of the same `try` expression. Loops and conditional
 * statements (`if`, `for`, `foreach`, `while`, `match`, `switch`) between `try`
 * blocks belong to other rules and do not contribute to the counter. Loops or
 * `try` blocks inside a `Closure` or arrow function are excluded from the count;
 * the rule tracks nesting only at the surrounding method level.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NestedTryDepthRule implements Rule
{
    /**
     * Constructs the rule with the given depth limit.
     *
     * @param int $maxDepth Maximum nesting depth of `try` statements per method
     * @throws InvalidArgumentException when maxDepth is negative
     */
    public function __construct(private int $maxDepth = 1)
    {
        if ($maxDepth < 0) {
            throw new InvalidArgumentException(
                sprintf('maxDepth must be a non-negative integer, %d given', $maxDepth),
            );
        }
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the method and returns errors for every `try` past the depth limit.
     *
     * @psalm-param ClassMethod $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $className = $scope->getClassReflection()?->getName() ?? 'unknown';
        $methodName = $node->name->toString();
        $scanner = new DepthScanner($this->maxDepth, $className, $methodName);

        return $scanner->scan($node);
    }
}
