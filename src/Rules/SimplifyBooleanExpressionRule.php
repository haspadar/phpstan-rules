<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Detects unnecessary comparisons of expressions with boolean literals.
 *
 * Comparisons like `$x == true`, `$x === false`, `$x != true`, `$x !== false`
 * do not add information: the expression itself already evaluates to a boolean
 * in a boolean context. Removing the comparison improves readability and avoids
 * subtle loose-comparison bugs (e.g. `0 == false` is true in PHP).
 *
 * Checks all four binary operators (==, ===, !=, !==) against the literals
 * `true` and `false`. Does not flag comparisons with `null`.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class SimplifyBooleanExpressionRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the method and returns errors for every boolean literal comparison.
     *
     * @param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var list<Equal|Identical|NotEqual|NotIdentical> $comparisons */
        $comparisons = (new NodeFinder())->find(
            $node->stmts ?? [],
            static fn(Node $n): bool => $n instanceof Equal
                || $n instanceof Identical
                || $n instanceof NotEqual
                || $n instanceof NotIdentical,
        );

        $errors = [];

        foreach ($comparisons as $comparison) {
            if (!$this->involvesBooleanLiteral($comparison)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                'Avoid unnecessary comparison with boolean literal. Use the expression directly.',
            )
                ->identifier('haspadar.simplifyBoolean')
                ->line($comparison->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if either operand of the comparison is a boolean literal (true or false).
     */
    private function involvesBooleanLiteral(Equal|Identical|NotEqual|NotIdentical $node): bool
    {
        return $this->isBooleanLiteral($node->left) || $this->isBooleanLiteral($node->right);
    }

    /**
     * Returns true if the expression is a ConstFetch resolving to true or false.
     */
    private function isBooleanLiteral(Node\Expr $expr): bool
    {
        if (!$expr instanceof ConstFetch) {
            return false;
        }

        $name = strtolower($expr->name->toString());

        return $name === 'true' || $name === 'false';
    }
}
