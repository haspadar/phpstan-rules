<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\NodeHelper\ChildNodes;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Finds the maximum boolean operator count (&&, ||, and, or, xor) in any single expression within a class method.
 * Each expression is evaluated independently — operators across separate expressions are not summed.
 * Nested scopes (closures, arrow functions, anonymous classes) are excluded from the count.
 * Bitwise operators & and | are excluded — their intent cannot be determined without type analysis.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class BooleanExpressionComplexityRule implements Rule
{
    /**
     * Constructs the rule with the given boolean operator limit.
     *
     * @throws InvalidArgumentException when maxOperators is not a positive integer
     */
    public function __construct(private int $maxOperators = 3)
    {
        if ($maxOperators <= 0) {
            throw new InvalidArgumentException(
                sprintf('maxOperators must be a positive integer, %d given', $maxOperators),
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
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $max = $this->maxOperatorsInSingleExpression($node);

        if ($max <= $this->maxOperators) {
            return [];
        }

        $className = $scope->getClassReflection()?->getName() ?? 'unknown';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has boolean expression complexity of %d. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $max,
                    $this->maxOperators,
                ),
            )
                ->identifier('haspadar.booleanComplexity')
                ->build(),
        ];
    }

    /**
     * Returns the maximum boolean operator count found in any single expression within the method body.
     * Each root operator is counted independently so that operators across separate statements are never summed.
     * Nested scopes (closures, arrow functions, anonymous classes) are not traversed.
     */
    private function maxOperatorsInSingleExpression(ClassMethod $node): int
    {
        $allOperators = $this->collectOperators(array_values($node->stmts ?? []));
        $max = 0;

        foreach ($allOperators as $candidate) {
            foreach ($allOperators as $other) {
                if ($other !== $candidate && $this->containsNode([$other], $candidate)) {
                    continue 2;
                }
            }

            $count = count($this->collectOperators([$candidate]));

            if ($count > $max) {
                $max = $count;
            }
        }

        return $max;
    }

    /**
     * Collects all boolean operators from the given nodes without entering nested scope boundaries.
     *
     * @param list<Node> $nodes
     * @return list<Node>
     */
    private function collectOperators(array $nodes): array
    {
        $result = [];

        foreach ($nodes as $node) {
            if ($this->isScopeBoundary($node)) {
                continue;
            }

            if ($this->isBooleanOperator($node)) {
                $result[] = $node;
            }

            $result = array_merge($result, $this->collectOperators(ChildNodes::of($node)));
        }

        return $result;
    }

    /**
     * Returns true if $target node is found anywhere inside $nodes without crossing scope boundaries.
     *
     * @param list<Node> $nodes
     */
    private function containsNode(array $nodes, Node $target): bool
    {
        foreach ($nodes as $node) {
            if ($this->isScopeBoundary($node)) {
                continue;
            }

            if ($node === $target) {
                return true;
            }

            if ($this->containsNode(ChildNodes::of($node), $target)) {
                return true;
            }
        }

        return false;
    }

    /** Returns true for nodes that introduce a new scope boundary. */
    private function isScopeBoundary(Node $node): bool
    {
        return $node instanceof Closure
            || $node instanceof ArrowFunction
            || $node instanceof Class_;
    }

    /** Returns true for nodes that are boolean operators. */
    private function isBooleanOperator(Node $node): bool
    {
        return $node instanceof BooleanAnd
            || $node instanceof BooleanOr
            || $node instanceof LogicalAnd
            || $node instanceof LogicalOr
            || $node instanceof LogicalXor;
    }
}
