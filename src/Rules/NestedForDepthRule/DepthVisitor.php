<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\NestedForDepthRule;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Tracks loop nesting depth across an AST traversal and records overruns.
 *
 * Each `for`, `foreach`, `while`, and `do-while` entered raises the depth
 * counter; the outermost loop ends up at depth 0, a loop directly inside
 * another loop body at depth 1, and so on. `Closure` and `ArrowFunction`
 * start a separate scope: while the visitor is inside one of them no loop
 * contributes to the counter.
 */
final class DepthVisitor extends NodeVisitorAbstract
{
    /** @var int Current nesting level of loop statements outside any closure scope */
    private int $depth = 0;

    /** @var int How many nested `Closure`/`ArrowFunction` scopes are currently entered */
    private int $closureDepth = 0;

    /** @var list<IdentifierRuleError> */
    private array $errors = [];

    /**
     * Constructs the visitor with the parameters needed to format errors.
     *
     * @param int $maxDepth Maximum allowed nesting depth
     * @param string $className FQCN of the surrounding class for the error message
     * @param string $methodName Method name for the error message
     */
    public function __construct(
        private readonly int $maxDepth,
        private readonly string $className,
        private readonly string $methodName,
    ) {}

    /**
     * Increments the relevant counter when a tracked node is entered.
     *
     * @param Node $node Node currently being entered by the traverser
     */
    #[Override]
    public function enterNode(Node $node): ?int
    {
        if ($node instanceof Closure || $node instanceof ArrowFunction) {
            ++$this->closureDepth;

            return null;
        }

        if ($this->closureDepth > 0) {
            return null;
        }

        if ($this->isLoop($node)) {
            ++$this->depth;
            $this->reportIfTooDeep($node);
        }

        return null;
    }

    /**
     * Decrements the relevant counter when a tracked node is left.
     *
     * @param Node $node Node currently being left by the traverser
     */
    #[Override]
    public function leaveNode(Node $node): ?int
    {
        if ($node instanceof Closure || $node instanceof ArrowFunction) {
            --$this->closureDepth;

            return null;
        }

        if ($this->closureDepth > 0) {
            return null;
        }

        if ($this->isLoop($node)) {
            --$this->depth;
        }

        return null;
    }

    /**
     * Returns the errors recorded across the traversal.
     *
     * @return list<IdentifierRuleError>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Tells whether the given node is a loop statement tracked by this visitor.
     *
     * @param Node $node Node to classify
     */
    private function isLoop(Node $node): bool
    {
        return $node instanceof For_
            || $node instanceof Foreach_
            || $node instanceof While_
            || $node instanceof Do_;
    }

    /**
     * Records an error when the current depth exceeds the configured maximum.
     */
    private function reportIfTooDeep(Node $node): void
    {
        $current = $this->depth - 1;

        if ($current <= $this->maxDepth) {
            return;
        }

        $this->errors[] = RuleErrorBuilder::message(
            sprintf(
                'Nested loop depth is %d in method %s::%s(). Maximum allowed is %d.',
                $current,
                $this->className,
                $this->methodName,
                $this->maxDepth,
            ),
        )
            ->identifier('haspadar.nestedForDepth')
            ->line($node->getStartLine())
            ->build();
    }
}
