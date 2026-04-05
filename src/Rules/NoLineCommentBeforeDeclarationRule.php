<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Forbids single-line comments before class, method, and property declarations.
 *
 * Detects // and # comments attached to ClassLike, ClassMethod, and Property
 * nodes. PHPDoc and block comments are allowed.
 *
 * @implements Rule<ClassLike>
 */
final readonly class NoLineCommentBeforeDeclarationRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassLike::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param ClassLike $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        $classError = $this->checkNode($node, $this->describeClassLike($node));

        if ($classError !== null) {
            $errors[] = $classError;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod) {
                $methodError = $this->checkNode($stmt, sprintf('Method %s()', $stmt->name->toString()));

                if ($methodError !== null) {
                    $errors[] = $methodError;
                }
            }

            if ($stmt instanceof Property) {
                foreach ($stmt->props as $prop) {
                    $propError = $this->checkNode($stmt, sprintf('Property $%s', $prop->name->toString()));

                    if ($propError !== null) {
                        $errors[] = $propError;

                        break;
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Checks whether any comment on the node is a single-line comment.
     */
    private function checkNode(Node $node, string $description): ?IdentifierRuleError
    {
        /** @var list<Comment> $comments */
        $comments = $node->getAttribute('comments') ?? [];

        foreach ($comments as $comment) {
            if ($comment instanceof Doc) {
                continue;
            }

            $text = $comment->getText();

            if (str_starts_with($text, '//') || str_starts_with($text, '#')) {
                return RuleErrorBuilder::message(
                    sprintf(
                        '%s has a line comment before its declaration; use a PHPDoc block instead.',
                        $description,
                    ),
                )
                    ->identifier('haspadar.noLineCommentBefore')
                    ->line($comment->getStartLine())
                    ->build();
            }
        }

        return null;
    }

    /**
     * Returns a human-readable description for a class-like node.
     */
    private function describeClassLike(ClassLike $node): string
    {
        if ($node->name === null) {
            return 'Anonymous class';
        }

        return sprintf('Class %s', $node->name->toString());
    }
}
