<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

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
    private int $maxComplexity;

    /** Constructs the rule with the given complexity limit */
    public function __construct(int $maxComplexity = 10)
    {
        $this->maxComplexity = $maxComplexity;
    }

    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        $complexity = $this->complexity($node);

        if ($complexity <= $this->maxComplexity) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection !== null ? $reflection->getName() : 'unknown';

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

        $branchingNodes = [
            If_::class,
            ElseIf_::class,
            While_::class,
            Do_::class,
            For_::class,
            Foreach_::class,
            Catch_::class,
            BooleanAnd::class,
            BooleanOr::class,
            LogicalAnd::class,
            LogicalOr::class,
            Ternary::class,
            Coalesce::class,
        ];

        $count = 1;

        foreach ($branchingNodes as $class) {
            $count += count($finder->findInstanceOf($node->stmts ?? [], $class));
        }

        foreach ($finder->findInstanceOf($node->stmts ?? [], Case_::class) as $case) {
            /** @var Case_ $case */
            if ($case->cond !== null) {
                $count++;
            }
        }

        foreach ($finder->findInstanceOf($node->stmts ?? [], MatchArm::class) as $arm) {
            /** @var MatchArm $arm */
            if ($arm->conds !== null) {
                $count++;
            }
        }

        return $count;
    }
}
