<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Forbids task-tracking comments inside method bodies.
 *
 * Scans all comment types (//, #, block, PHPDoc) attached to nodes inside
 * a ClassMethod for configurable keywords (case-insensitive, word boundary).
 *
 * @implements Rule<ClassMethod>
 */
final readonly class TodoCommentRule implements Rule
{
    /** @var list<string> */
    private array $keywords;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{
     *     keywords?: list<string>
     * } $options
     */
    public function __construct(array $options = [])
    {
        $this->keywords = $options['keywords'] ?? ['TODO', 'FIXME', 'XXX'];
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

        $errors = [];
        $pattern = $this->buildPattern();
        $allNodes = (new NodeFinder())->findInstanceOf($node->stmts, Node::class);

        foreach ($allNodes as $child) {
            foreach ($this->collectViolations($child, $pattern) as $error) {
                $errors[] = $error;
            }
        }

        $methodDoc = $node->getDocComment();

        if ($methodDoc !== null && preg_match($pattern, $methodDoc->getText()) === 1) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'TODO comment found on line %d. Resolve the issue or create a ticket instead.',
                    $methodDoc->getStartLine(),
                ),
            )
                ->identifier('haspadar.todoComment')
                ->line($methodDoc->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Builds a case-insensitive regex pattern from the configured keywords.
     *
     * @return non-empty-string
     */
    private function buildPattern(): string
    {
        $escaped = array_map(
            static fn(string $keyword): string => preg_quote($keyword, '/'),
            $this->keywords,
        );

        return sprintf('/\b(%s)\b/i', implode('|', $escaped));
    }

    /**
     * Returns errors for forbidden comments attached to a node.
     *
     * @param non-empty-string $pattern
     * @return list<IdentifierRuleError>
     */
    private function collectViolations(Node $node, string $pattern): array
    {
        /** @var list<Comment> $comments */
        $comments = $node->getAttribute('comments') ?? [];
        $errors = [];

        foreach ($comments as $comment) {
            if (preg_match($pattern, $comment->getText()) === 1) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'TODO comment found on line %d. Resolve the issue or create a ticket instead.',
                        $comment->getStartLine(),
                    ),
                )
                    ->identifier('haspadar.todoComment')
                    ->line($comment->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }
}
