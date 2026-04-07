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
use PHPStan\ShouldNotHappenException;

/**
 * Counts return statements in a class method and reports an error when the count exceeds the limit.
 *
 * Abstract methods and methods without body are skipped.
 * Nested scopes (closures, arrow functions) are not traversed — their return statements
 * are excluded from the count.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ReturnCountRule implements Rule
{
    /**
     * Constructs the rule with the given return statement limit.
     *
     * @throws InvalidArgumentException when max is not a positive integer
     */
    public function __construct(private int $max = 1)
    {
        if ($max <= 0) {
            throw new InvalidArgumentException(
                sprintf('max must be a positive integer, %d given', $max),
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
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if ($node->stmts === null) {
            return [];
        }

        $count = $this->countReturns(array_values($node->stmts));

        if ($count <= $this->max) {
            return [];
        }

        $className = $scope->getClassReflection()?->getName() ?? 'unknown';

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
     * Recursively counts return statements in the given list of nodes, without entering scope boundaries.
     *
     * @param list<Node> $stmts
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
