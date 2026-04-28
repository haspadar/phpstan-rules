<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\NestedSwitchRule;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Tracks switch nesting depth across an AST traversal and records violations.
 *
 * Each `Stmt\Switch_` entered raises the depth counter. When a nested switch
 * (depth > 0) is encountered, an error is recorded. `Closure` and
 * `ArrowFunction` bodies are counted separately — switches inside them do not
 * contribute to the outer depth.
 */
final class NestedSwitchVisitor extends NodeVisitorAbstract
{
    /** @var int Current nesting level of switch statements outside any closure scope */
    private int $depth = 0;

    /** @var int How many nested Closure/ArrowFunction scopes are currently entered */
    private int $closureDepth = 0;

    /** @var list<IdentifierRuleError> */
    private array $errors = [];

    /**
     * Increments the relevant counter when a tracked node is entered.
     */
    #[Override]
    public function enterNode(Node $node): null
    {
        if ($node instanceof Closure || $node instanceof ArrowFunction) {
            ++$this->closureDepth;

            return null;
        }

        if ($this->closureDepth > 0) {
            return null;
        }

        if ($node instanceof Switch_) {
            if ($this->depth > 0) {
                $this->errors[] = RuleErrorBuilder::message(
                    'Nested switch statements are forbidden — extract the inner switch into a separate method.',
                )
                    ->identifier('haspadar.nestedSwitch')
                    ->line($node->getStartLine())
                    ->build();
            }

            ++$this->depth;
        }

        return null;
    }

    /**
     * Decrements the relevant counter when a tracked node is exited.
     */
    #[Override]
    public function leaveNode(Node $node): null
    {
        if ($node instanceof Closure || $node instanceof ArrowFunction) {
            --$this->closureDepth;

            return null;
        }

        if ($this->closureDepth > 0) {
            return null;
        }

        if ($node instanceof Switch_) {
            --$this->depth;
        }

        return null;
    }

    /**
     * Returns the list of errors found during traversal.
     *
     * @return list<IdentifierRuleError>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
