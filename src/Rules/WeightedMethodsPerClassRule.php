<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\MatchArm;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports a class whose weighted method count (sum of cyclomatic complexities) exceeds the configured limit.
 *
 * @implements Rule<Class_>
 */
final readonly class WeightedMethodsPerClassRule implements Rule
{
    /**
     * Constructs the rule with the given WMC limit.
     *
     * @param int $maxWmc Maximum Weighted Methods per Class — sum of cyclomatic complexities of all methods.
     * @throws InvalidArgumentException
     */
    public function __construct(private int $maxWmc = 50)
    {
        if ($maxWmc <= 0) {
            throw new InvalidArgumentException(
                sprintf('maxWmc must be a positive integer, %d given', $maxWmc),
            );
        }
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $wmc = $this->wmc($node);

        if ($wmc <= $this->maxWmc) {
            return [];
        }

        if ($node->isAnonymous()) {
            return [];
        }

        assert($node->name !== null);

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Class %s has weighted method complexity of %d. Maximum allowed is %d.',
                    $node->name->toString(),
                    $wmc,
                    $this->maxWmc,
                ),
            )
                ->identifier('haspadar.weightedMethods')
                ->build(),
        ];
    }

    /**
     * Computes the weighted method count for a class.
     *
     * @param Class_ $node
     */
    private function wmc(Node $node): int
    {
        $total = 0;

        foreach ($node->getMethods() as $method) {
            $total += $this->complexity($method);
        }

        return $total;
    }

    /** Computes the cyclomatic complexity of a method. */
    private function complexity(ClassMethod $method): int
    {
        $finder = new NodeFinder();
        $count = 1;

        foreach ($finder->find($method->stmts ?? [], $this->isBranchingNode(...)) as $candidate) {
            if ($candidate instanceof Case_ && $candidate->cond === null) {
                continue;
            }

            if ($candidate instanceof MatchArm && $candidate->conds === null) {
                continue;
            }

            $count++;
        }

        return $count;
    }

    /** Returns true for nodes that contribute to cyclomatic complexity. */
    private function isBranchingNode(Node $node): bool
    {
        return $this->isControlFlowNode($node) || $this->isLogicalOrConditionalNode($node);
    }

    /** Returns true for control flow statement nodes. */
    private function isControlFlowNode(Node $node): bool
    {
        return $node instanceof If_
            || $node instanceof ElseIf_
            || $node instanceof While_
            || $node instanceof Do_
            || $node instanceof For_
            || $node instanceof Foreach_
            || $node instanceof Catch_
            || $node instanceof Case_
            || $node instanceof MatchArm;
    }

    /** Returns true for logical and conditional expression nodes. */
    private function isLogicalOrConditionalNode(Node $node): bool
    {
        return $node instanceof BooleanAnd
            || $node instanceof BooleanOr
            || $node instanceof LogicalAnd
            || $node instanceof LogicalOr
            || $node instanceof Ternary
            || $node instanceof Coalesce;
    }
}
