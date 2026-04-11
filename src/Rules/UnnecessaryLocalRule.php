<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports local variables that are assigned and immediately returned or thrown.
 * Such variables add unnecessary complexity. The expression should be
 * returned or thrown directly. Variables with @var PHPDoc are excluded
 * because they carry type information.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class UnnecessaryLocalRule implements Rule
{
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
        if ($node->stmts === null) {
            return [];
        }

        $errors = [];

        foreach ($this->findUnnecessaryPairs(array_values($node->stmts)) as [$name, $line, $kind]) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Variable $%s is assigned and immediately %s. %s the expression directly.',
                    $name,
                    $kind,
                    $kind === 'returned' ? 'Return' : 'Throw',
                ),
            )
                ->identifier('haspadar.unnecessaryLocal')
                ->line($line)
                ->build();
        }

        return $errors;
    }

    /**
     * Finds assign-then-return/throw pairs in a flat statement list.
     *
     * @param list<Node\Stmt> $stmts
     * @return list<array{string, int, string}>
     */
    private function findUnnecessaryPairs(array $stmts): array
    {
        $pairs = [];

        foreach ($stmts as $idx => $current) {
            if (!array_key_exists($idx + 1, $stmts)) {
                continue;
            }

            $assigned = $this->extractAssignment($current);

            if ($assigned === null) {
                continue;
            }

            [$name, $line] = $assigned;

            $kind = $this->matchesReturnOrThrow($stmts[$idx + 1], $name);

            if ($kind === null) {
                continue;
            }

            if ($this->hasVarPhpDoc($current)) {
                continue;
            }

            $pairs[] = [$name, $line, $kind];
        }

        return $pairs;
    }

    /**
     * Extracts variable name and line from an assignment expression statement.
     *
     * @return array{string, int}|null
     */
    private function extractAssignment(Node\Stmt $stmt): ?array
    {
        if (!$stmt instanceof Expression) {
            return null;
        }

        if (!$stmt->expr instanceof Assign) {
            return null;
        }

        $var = $stmt->expr->var;

        if (!$var instanceof Variable || !is_string($var->name)) {
            return null;
        }

        return [$var->name, $stmt->getStartLine()];
    }

    /**
     * Checks if the statement is a return or throw of the given variable name.
     */
    private function matchesReturnOrThrow(Node\Stmt $stmt, string $name): ?string
    {
        if ($stmt instanceof Return_ && $stmt->expr instanceof Variable && is_string($stmt->expr->name)) {
            if ($stmt->expr->name === $name) {
                return 'returned';
            }
        }

        if ($stmt instanceof Expression && $stmt->expr instanceof Throw_) {
            if ($stmt->expr->expr instanceof Variable && is_string(
                $stmt->expr->expr->name,
            ) && $stmt->expr->expr->name === $name) {
                return 'thrown';
            }
        }

        return null;
    }

    /**
     * Checks if the statement has a @var PHPDoc comment.
     */
    private function hasVarPhpDoc(Node\Stmt $stmt): bool
    {
        $docComment = $stmt->getDocComment();

        if (!$docComment instanceof Doc) {
            return false;
        }

        return str_contains($docComment->getText(), '@var');
    }
}
