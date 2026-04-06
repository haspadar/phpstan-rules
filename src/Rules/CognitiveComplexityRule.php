<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\NodeHelper\ChildNodes;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\While_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Measures cognitive complexity of each class method per the SonarSource specification.
 *
 * Nesting-increment structures (if, else if, for, foreach, while, do, switch, catch, ternary, null coalesce)
 * each add 1 plus a penalty equal to the current nesting depth.
 * Structural increments (else, finally) add 1 with no nesting penalty.
 * Break/continue with a label add 1.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class CognitiveComplexityRule implements Rule
{
    /**
     * Constructs the rule with the given complexity limit.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(private int $maxComplexity = 10)
    {
        if ($maxComplexity <= 0) {
            throw new InvalidArgumentException(
                sprintf('maxComplexity must be a positive integer, %d given', $maxComplexity),
            );
        }
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param ClassMethod $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $complexity = $this->calculate($node->stmts ?? [], 0);

        if ($complexity <= $this->maxComplexity) {
            return [];
        }

        $className = $scope->getClassReflection()?->getName() ?? 'unknown';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has cognitive complexity of %d. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $complexity,
                    $this->maxComplexity,
                ),
            )
                ->identifier('haspadar.cognitiveComplexity')
                ->build(),
        ];
    }

    /**
     * Recursively calculates cognitive complexity for a list of statements.
     *
     * @param list<Node> $stmts
     * @phpstan-param array<Node> $stmts
     */
    private function calculate(array $stmts, int $depth): int
    {
        $score = 0;

        foreach ($stmts as $stmt) {
            $score += $this->scoreNode($stmt, $depth);
        }

        return $score;
    }

    /**
     * Returns the cognitive complexity score contributed by a single node.
     */
    private function scoreNode(Node $node, int $depth): int
    {
        if ($node instanceof If_) {
            return 1 + $depth + $this->scoreIf($node, $depth);
        }

        if ($node instanceof Switch_) {
            return 1 + $depth + $this->scoreCases($node->cases, $depth + 1);
        }

        return $this->scoreBranch($node, $depth);
    }

    /**
     * Returns the score for branch nodes (elseif, else, catch, finally), delegates to loop scoring.
     */
    private function scoreBranch(Node $node, int $depth): int
    {
        if ($node instanceof ElseIf_ || $node instanceof Catch_) {
            return 1 + $depth + $this->calculate($node->stmts, $depth + 1);
        }

        if ($node instanceof Else_ || $node instanceof Finally_) {
            return 1 + $this->calculate($node->stmts, $depth + 1);
        }

        return $this->scoreLoopOrJump($node, $depth);
    }

    /**
     * Returns the complexity score for loop and ternary nodes, delegates jump scoring.
     */
    private function scoreLoopOrJump(Node $node, int $depth): int
    {
        if ($node instanceof While_ || $node instanceof Do_ || $node instanceof For_ || $node instanceof Foreach_) {
            return 1 + $depth + $this->calculate($node->stmts, $depth + 1);
        }

        if ($node instanceof Ternary || $node instanceof Coalesce) {
            return 1 + $depth + $this->scoreChildren($node, $depth);
        }

        return $this->scoreJump($node, $depth);
    }

    /**
     * Returns 1 for labeled break/continue, delegates to child scoring otherwise.
     */
    private function scoreJump(Node $node, int $depth): int
    {
        if (($node instanceof Break_ || $node instanceof Continue_) && $node->num !== null) {
            return 1;
        }

        return $this->scoreChildren($node, $depth);
    }

    /**
     * Scores an if statement including its elseif/else branches.
     */
    private function scoreIf(If_ $node, int $depth): int
    {
        $score = $this->calculate($node->stmts, $depth + 1);

        foreach ($node->elseifs as $elseif) {
            $score += $this->scoreNode($elseif, $depth);
        }

        if ($node->else !== null) {
            $score += $this->scoreNode($node->else, $depth);
        }

        return $score;
    }

    /**
     * Scores switch cases, skipping default (null condition).
     *
     * @param list<Case_> $cases
     * @phpstan-param array<Case_> $cases
     */
    private function scoreCases(array $cases, int $depth): int
    {
        $score = 0;

        foreach ($cases as $case) {
            $score += $this->calculate($case->stmts, $depth);
        }

        return $score;
    }

    /**
     * Recursively scores child nodes that are not handled by specific branch logic.
     */
    private function scoreChildren(Node $node, int $depth): int
    {
        $score = 0;

        foreach (ChildNodes::of($node) as $child) {
            $score += $this->scoreNode($child, $depth);
        }

        return $score;
    }
}
