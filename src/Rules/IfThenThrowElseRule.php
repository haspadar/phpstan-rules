<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports if statements whose then-branch ends with a throw and still have an else or elseif branch.
 *
 * When the then-branch unconditionally throws, control never reaches the code
 * after the if/else pair. The else keyword adds no information and only deepens
 * nesting. Remove the else and leave the alternative body at the original
 * indentation level (guard-clause / early-throw pattern).
 *
 * Only throw is checked — return is handled separately by ReturnCountRule.
 * Closure and arrow-function bodies are scanned independently; a throw inside
 * a nested closure does not trigger this rule for the outer if statement.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class IfThenThrowElseRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the method and returns errors for every if/throw/else pattern.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var list<If_> $ifNodes */
        $ifNodes = (new NodeFinder())->find(
            $node->stmts ?? [],
            static fn(Node $n): bool => $n instanceof If_,
        );

        $errors = [];

        foreach ($ifNodes as $ifNode) {
            if (!$this->thenEndsWithThrow($ifNode)) {
                continue;
            }

            if ($ifNode->else === null && $ifNode->elseifs === []) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                'Remove the else branch — the if block always throws.',
            )
                ->identifier('haspadar.ifThenThrowElse')
                ->line($ifNode->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the last statement in the if-branch is a throw expression statement.
     */
    private function thenEndsWithThrow(If_ $node): bool
    {
        if ($node->stmts === []) {
            return false;
        }

        $last = $node->stmts[count($node->stmts) - 1];

        return $last instanceof Expression && $last->expr instanceof Throw_;
    }
}
