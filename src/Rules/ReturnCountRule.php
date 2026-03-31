<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\NodeHelper\ChildNodes;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Counts return statements in a class method and reports an error when the count
 * exceeds the configured limit. Abstract methods and methods without body are skipped.
 * Nested scopes (closures, arrow functions) are not traversed — their return statements
 * are excluded from the count.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ReturnCountRule implements Rule
{
    private int $max;

    /**
     * Constructs the rule with the given return statement limit
     *
     * @throws InvalidArgumentException when max is not a positive integer
     */
    public function __construct(int $max = 1)
    {
        if ($max <= 0) {
            throw new InvalidArgumentException(
                sprintf('max must be a positive integer, %d given', $max),
            );
        }

        $this->max = $max;
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
    public function processNode(
        Node $node,
        Scope $scope,
    ): array {
        /** @var ClassMethod $node */
        if ($node->stmts === null) {
            return [];
        }

        $count = $this->countReturns($node->stmts);

        if ($count <= $this->max) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection !== null ? $reflection->getName() : 'unknown'; // @codeCoverageIgnore

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has %d return statements. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $count,
                    $this->max,
                ),
            )
                ->identifier('haspadar.returnCount')
                ->build(),
        ];
    }

    /**
     * Recursively counts return statements in the given list of nodes,
     * without entering nested scope boundaries
     *
     * @param array<Node> $stmts
     */
    private function countReturns(array $stmts): int
    {
        $count = 0;

        foreach ($stmts as $stmt) {
            if ($stmt instanceof Closure || $stmt instanceof ArrowFunction) {
                continue;
            }

            if ($stmt instanceof Return_) {
                $count++;
            }

            $count += $this->countReturns(ChildNodes::of($stmt));
        }

        return $count;
    }
}
