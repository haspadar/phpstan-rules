<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\NodeHelper\ChildNodes;
use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Counts executable statements in a class method and reports an error when the count
 * exceeds the configured limit. Executable statements are all Stmt nodes except
 * structural/declarative ones (Nop, Label, Declare_, Else_, Block).
 * Nested scopes (closures, arrow functions, anonymous classes, property hooks) are
 * not traversed — their statements are excluded from the count.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class StatementCountRule implements Rule
{
    /**
     * Constructs the rule with the given statement limit
     *
     * @throws InvalidArgumentException when maxStatements is not a positive integer
     */
    public function __construct(private int $maxStatements = 30)
    {
        if ($maxStatements <= 0) {
            throw new InvalidArgumentException(
                sprintf('maxStatements must be a positive integer, %d given', $maxStatements),
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
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $count = $this->countStatements($node->stmts ?? []);

        if ($count <= $this->maxStatements) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection !== null
            ? $reflection->getName()
            : 'unknown';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() has %d executable statements. Maximum allowed is %d.',
                    $className,
                    $node->name->toString(),
                    $count,
                    $this->maxStatements,
                ),
            )
                ->identifier('haspadar.executableStatements')
                ->build(),
        ];
    }

    /**
     * Recursively counts executable statements in the given list of nodes,
     * without entering nested scope boundaries
     *
     * @param list<Node> $stmts
     */
    private function countStatements(array $stmts): int
    {
        $count = 0;

        foreach ($stmts as $stmt) {
            if ($this->isScopeBoundary($stmt)) {
                continue;
            }

            if ($this->isExecutable($stmt)) {
                $count++;
            }

            $count += $this->countStatements(ChildNodes::of($stmt));
        }

        return $count;
    }

    /**
     * Returns true for nodes that introduce a new scope boundary
     */
    private function isScopeBoundary(Node $node): bool
    {
        return $node instanceof Closure
            || $node instanceof ArrowFunction
            || $node instanceof Stmt\Class_
            || $node instanceof Stmt\Function_
            || $node instanceof Node\PropertyHook;
    }

    /**
     * Returns true for executable statement nodes (all Stmt except structural ones)
     */
    private function isExecutable(Node $node): bool
    {
        return $node instanceof Stmt
            && !$node instanceof Stmt\Nop
            && !$node instanceof Stmt\Label
            && !$node instanceof Stmt\Declare_
            && !$node instanceof Stmt\Else_
            && !$node instanceof Stmt\Block;
    }
}
