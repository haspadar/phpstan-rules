<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\NestedIfDepthRule\DepthScanner;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;

/**
 * Reports a class method whose nested `if` depth exceeds the configured limit.
 *
 * Depth is counted relative to the method body: an outermost `if` has depth 0,
 * an `if` directly inside another `if` body has depth 1, and so on. Sibling
 * `elseif` and `else` branches do not increase the depth — they are alternative
 * paths of the same `if` expression. Each `Closure` or arrow function starts a
 * new scope and the depth counter resets to 0. The `match` and `switch`
 * constructs are not counted as `if` statements.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NestedIfDepthRule implements Rule
{
    /**
     * Constructs the rule with the given depth limit.
     *
     * @param int $maxDepth Maximum nesting depth of `if` statements per method.
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
     * Analyses the method and returns errors for every `if` past the depth limit.
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
