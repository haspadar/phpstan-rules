<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\NestedForDepthRule\DepthScanner;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;

/**
 * Reports a class method whose nested loop depth exceeds the configured limit.
 *
 * Depth is counted relative to the method body across `for`, `foreach`,
 * `while`, and `do-while`: an outermost loop has depth 0, a loop directly
 * inside another loop body has depth 1, and so on. Conditional statements
 * (`if`, `else`, `match`, `switch`) between loops do not increase the
 * depth — they belong to other rules. Each `Closure` or arrow function
 * starts a new scope and the depth counter resets to 0.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NestedForDepthRule implements Rule
{
    /**
     * Constructs the rule with the given depth limit.
     *
     * @param int $maxDepth Maximum nesting depth of loop statements per method
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
     * Analyses the method and returns errors for every loop past the depth limit.
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
