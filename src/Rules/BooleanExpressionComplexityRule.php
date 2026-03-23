<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Counts boolean operators (&&, ||, and, or, xor) per expression in a class method
 * and reports an error when the total exceeds the configured limit.
 * Bitwise operators & and | are excluded — their intent cannot be determined without type analysis.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class BooleanExpressionComplexityRule implements Rule
{
    private int $maxOperators;

    /**
     * Constructs the rule with the given boolean operator limit
     *
     * @throws InvalidArgumentException when maxOperators is not a positive integer
     */
    public function __construct(int $maxOperators = 3)
    {
        if ($maxOperators <= 0) {
            throw new InvalidArgumentException(
                sprintf('maxOperators must be a positive integer, %d given', $maxOperators),
            );
        }

        $this->maxOperators = $maxOperators;
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
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        $count = $this->countOperators($node);

        if ($count <= $this->maxOperators) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection !== null ? $reflection->getName() : 'unknown';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has boolean expression complexity of %d. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $count,
                    $this->maxOperators,
                ),
            )
                ->identifier('haspadar.booleanComplexity')
                ->build(),
        ];
    }

    /** Counts boolean operators in all expressions within the method body */
    private function countOperators(ClassMethod $node): int
    {
        $finder = new NodeFinder();

        return count($finder->find($node->stmts ?? [], $this->isBooleanOperator(...)));
    }

    /** Returns true for nodes that are boolean operators */
    private function isBooleanOperator(Node $node): bool
    {
        return $node instanceof BooleanAnd
            || $node instanceof BooleanOr
            || $node instanceof LogicalAnd
            || $node instanceof LogicalOr
            || $node instanceof LogicalXor;
    }
}
