<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
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

        return array_merge($errors, $this->checkMembers($node));
    }

    /**
     * Checks all methods and properties inside the class-like for line comments.
     *
     * @return list<IdentifierRuleError>
     */
    private function checkMembers(ClassLike $node): array
    {
        $errors = [];

        foreach ($node->stmts as $stmt) {
            $error = $this->checkMember($stmt);

            if ($error !== null) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * Checks a single class member (method or property) for line comments.
     */
    private function checkMember(Node $stmt): ?IdentifierRuleError
    {
        if ($stmt instanceof ClassMethod) {
            return $this->checkNode($stmt, sprintf('Method %s()', $stmt->name->toString()));
        }

        if ($stmt instanceof Property) {
            return $this->checkProperty($stmt);
        }

        return null;
    }

    /**
     * Checks a property statement for line comments.
     */
    private function checkProperty(Property $property): ?IdentifierRuleError
    {
        foreach ($property->props as $prop) {
            $error = $this->checkNode($property, sprintf('Property $%s', $prop->name->toString()));

            if ($error !== null) {
                return $error;
            }
        }

        return null;
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
        $kind = match (true) {
            $node instanceof Interface_ => 'Interface',
            $node instanceof Enum_ => 'Enum',
            $node instanceof Trait_ => 'Trait',
            default => 'Class',
        };

        return sprintf('%s %s', $kind, $node->name?->toString() ?? 'anonymous');
    }
}
