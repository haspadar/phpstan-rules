<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\MultipleVariableDeclarationsRule\StatementListCollector;
use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Node\FileNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Forbids two forms that pack several declarations into one source unit.
 *
 * The rule reports a chained assignment such as `$a = $b = 1;` because the
 * right-hand side carries another `Assign` node, and it reports two adjacent
 * statements that share a single source line because each statement deserves
 * its own line. A `for` initialiser may carry several expressions and is not
 * affected by the per-line check; destructuring assignments and assignments
 * used as conditions are not chained and remain untouched.
 *
 * @implements Rule<FileNode>
 */
final readonly class MultipleVariableDeclarationsRule implements Rule
{
    private bool $allowChainedNull;

    private StatementListCollector $statementLists;

    /**
     * Constructs the rule with the optional opt-in for `null` chains.
     *
     * @param array{allowChainedNull?: bool} $options Switch that allows `$a = $b = null;` when enabled.
     */
    public function __construct(array $options = [])
    {
        $this->allowChainedNull = $options['allowChainedNull'] ?? false;
        $this->statementLists = new StatementListCollector();
    }

    #[Override]
    public function getNodeType(): string
    {
        return FileNode::class;
    }

    /**
     * Analyses the file and returns errors for chained assignments and multi-statement lines.
     *
     * @psalm-param FileNode $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        return array_merge(
            $this->findChainedAssignments($node),
            $this->findMultipleStatementsPerLine($node),
        );
    }

    /**
     * Reports each statement whose top-level assignment chains into another assignment.
     *
     * Only the outermost link of a chain is reported so a single chained
     * statement produces one error regardless of nesting depth.
     *
     * @return list<IdentifierRuleError>
     */
    private function findChainedAssignments(FileNode $node): array
    {
        /** @var list<Expression> $expressions */
        $expressions = (new NodeFinder())->findInstanceOf($node->getNodes(), Expression::class);
        $errors = [];

        foreach ($expressions as $expression) {
            if (!$expression->expr instanceof Assign) {
                continue;
            }

            if (!$expression->expr->expr instanceof Assign) {
                continue;
            }

            if ($this->allowChainedNull && $this->isAllNullChain($expression->expr)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                'Chained assignment is forbidden: split into separate statements.',
            )
                ->identifier('haspadar.multipleVarDecl')
                ->line($expression->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Reports adjacent statements that share a line within any statement collection.
     *
     * Each statement list — file-level, function/method/closure bodies, branches
     * of `if`/`else`, loop bodies, `switch` cases and `try`/`catch`/`finally` —
     * is scanned and every pair of neighbours with an identical start line
     * yields one error pinned to the second statement.
     *
     * @return list<IdentifierRuleError>
     */
    private function findMultipleStatementsPerLine(FileNode $node): array
    {
        $errors = [];

        foreach ($this->statementLists->collect($node) as $stmts) {
            $previousLine = 0;

            foreach ($stmts as $stmt) {
                if ($stmt->getStartLine() === $previousLine) {
                    $errors[] = RuleErrorBuilder::message(
                        'Only one statement is allowed per line.',
                    )
                        ->identifier('haspadar.multipleVarDecl')
                        ->line($stmt->getStartLine())
                        ->build();
                }

                $previousLine = $stmt->getStartLine();
            }
        }

        return $errors;
    }

    /**
     * Returns true when every assignment in the chain has `null` on its rightmost side.
     */
    private function isAllNullChain(Assign $assign): bool
    {
        $current = $assign;

        while ($current->expr instanceof Assign) {
            $current = $current->expr;
        }

        return $current->expr instanceof ConstFetch
            && $current->expr->name->toLowerString() === 'null';
    }
}
