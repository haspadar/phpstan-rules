<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\AssignOp\Concat as ConcatAssign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Forbids concatenation of string literals using the dot operator.
 * When allowMixed is false (default), any concatenation expression
 * containing a string literal is reported. When allowMixed is true,
 * only concatenation where both sides contain string literals is reported.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class StringLiteralsConcatenationRule implements Rule
{
    private bool $allowMixed;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{allowMixed?: bool} $options Toggle that allows concatenation between a literal and a non-literal operand.
     */
    public function __construct(array $options = [])
    {
        $this->allowMixed = $options['allowMixed'] ?? false;
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
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->stmts === null) {
            return [];
        }

        $finder = new NodeFinder();

        /** @var list<Concat|ConcatAssign> $concats */
        $concats = $finder->find(
            $node->stmts,
            static fn(Node $n): bool => $n instanceof Concat || $n instanceof ConcatAssign,
        );

        $nested = $this->collectNestedConcats($concats);
        $errors = [];

        foreach ($concats as $concat) {
            if (in_array($concat, $nested, true)) {
                continue;
            }

            if ($this->isViolation($concat)) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'String literal concatenation found on line %d. Use sprintf() or string interpolation instead.',
                        $concat->getStartLine(),
                    ),
                )
                    ->identifier('haspadar.stringConcat')
                    ->line($concat->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * Collects concat nodes that are children of other concat nodes.
     *
     * @param list<Concat|ConcatAssign> $concats
     * @return list<Concat|ConcatAssign>
     */
    private function collectNestedConcats(array $concats): array
    {
        $nested = [];

        foreach ($concats as $concat) {
            if ($concat instanceof Concat && $concat->left instanceof Concat) {
                $nested[] = $concat->left;
            }

            if ($concat instanceof Concat && $concat->right instanceof Concat) {
                $nested[] = $concat->right;
            }
        }

        return $nested;
    }

    /**
     * Checks whether the concatenation expression violates the rule.
     */
    private function isViolation(Concat|ConcatAssign $concat): bool
    {
        if ($this->allowMixed) {
            return $this->bothSidesContainLiteral($concat);
        }

        return $this->containsStringLiteral($concat);
    }

    /**
     * Checks whether both sides of a concat contain a string literal.
     */
    private function bothSidesContainLiteral(Concat|ConcatAssign $concat): bool
    {
        if ($concat instanceof ConcatAssign) {
            return $this->containsStringLiteral($concat->var)
                && $this->containsStringLiteral($concat->expr);
        }

        return $this->containsStringLiteral($concat->left)
            && $this->containsStringLiteral($concat->right);
    }

    /**
     * Checks whether an expression contains a string literal.
     */
    private function containsStringLiteral(Node $node): bool
    {
        if ($node instanceof String_) {
            return true;
        }

        $found = (new NodeFinder())->findFirst(
            $node,
            static fn(Node $n): bool => $n instanceof String_,
        );

        return $found !== null;
    }
}
