<?php

declare(strict_types = 1);

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

/** @implements Rule<ClassMethod> */
final readonly class CyclomaticComplexityRule implements Rule
{
    /**
     * Constructs the rule with the given complexity limit
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

    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $complexity = $this->complexity($node);

        if ($complexity <= $this->maxComplexity) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection !== null
            ? $reflection->getName()
            : 'unknown';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has cyclomatic complexity of %d. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $complexity,
                    $this->maxComplexity,
                ),
            )
                ->identifier('haspadar.cyclomaticComplexity')
                ->build(),
        ];
    }

    /** Computes the cyclomatic complexity of a method */
    private function complexity(ClassMethod $node): int
    {
        $finder = new NodeFinder();
        $count = 1;

        foreach ($finder->find($node->stmts ?? [], $this->isBranchingNode(...)) as $candidate) {
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

    /** Returns true for nodes that contribute to cyclomatic complexity */
    private function isBranchingNode(Node $node): bool
    {
        return $this->isControlFlowNode($node) || $this->isLogicalOrConditionalNode($node);
    }

    /** Returns true for control flow statement nodes */
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

    /** Returns true for logical and conditional expression nodes */
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
