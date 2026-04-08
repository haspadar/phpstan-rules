<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Forbids comments inside method bodies.
 *
 * Detects //, #, and block comments attached to any node inside a ClassMethod.
 * Comments whose text starts with @ are allowed because they are suppress
 * directives used by static analysis tools.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NoInlineCommentRule implements Rule
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
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->stmts === null) {
            return [];
        }

        $errors = [];
        $allNodes = (new NodeFinder())->findInstanceOf($node->stmts, Node::class);

        foreach ($allNodes as $child) {
            foreach ($this->findForbiddenComments($child) as $error) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * Returns errors for forbidden comments attached to a node.
     *
     * @return list<IdentifierRuleError>
     */
    private function findForbiddenComments(Node $node): array
    {
        /** @var list<Comment> $comments */
        $comments = $node->getAttribute('comments') ?? [];
        $errors = [];

        foreach ($comments as $comment) {
            if ($comment instanceof Doc) {
                continue;
            }

            if ($this->isSuppressDirective($comment)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Inline comment found on line %d; comments inside method bodies are forbidden.',
                    $comment->getStartLine(),
                ),
            )
                ->identifier('haspadar.noInlineComment')
                ->line($comment->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Checks whether the comment is a suppress directive starting with @.
     */
    private function isSuppressDirective(Comment $comment): bool
    {
        $text = $comment->getText();

        if (str_starts_with($text, '//')) {
            return str_starts_with(ltrim(substr($text, 2)), '@');
        }

        if (str_starts_with($text, '#')) {
            return str_starts_with(ltrim(substr($text, 1)), '@');
        }

        if (str_starts_with($text, '/*')) {
            return str_starts_with(ltrim(substr($text, 2)), '@');
        }

        return false;
    }
}
