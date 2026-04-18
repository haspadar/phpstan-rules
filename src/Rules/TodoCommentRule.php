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
 * Forbids task-tracking comments unless they follow the puzzle-driven format linked to an issue.
 *
 * Scans every comment attached to a node inside a ClassMethod body and the
 * method's own PHPDoc for generic task markers (TODO, FIXME, XXX, todo, fixme, xxx).
 * A comment containing such a marker is allowed only when every line carrying
 * a marker also matches the configured issue format (default: `@todo #NUMBER ...`).
 *
 * @implements Rule<ClassMethod>
 */
final readonly class TodoCommentRule implements Rule
{
    private const string MARKER_PATTERN = '/\b(TODO|FIXME|XXX|todo|fixme|xxx)\b/';

    /** @var non-empty-string */
    private string $issueFormat;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{
     *     issueFormat?: non-empty-string
     * } $options
     */
    public function __construct(array $options = [])
    {
        $this->issueFormat = $options['issueFormat'] ?? '/@todo\s+#\d+\b/i';
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
     * @throws \PHPStan\ShouldNotHappenException
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
            foreach ($this->collectViolations($child) as $error) {
                $errors[] = $error;
            }
        }

        $methodDoc = $node->getDocComment();

        if ($methodDoc !== null && !$this->isAllowed($methodDoc->getText())) {
            $errors[] = $this->buildError($methodDoc->getStartLine());
        }

        return $errors;
    }

    /**
     * Returns errors for forbidden comments attached to a node.
     *
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function collectViolations(Node $node): array
    {
        /** @var list<Comment> $comments */
        $comments = $node->getAttribute('comments') ?? [];
        $errors = [];

        foreach ($comments as $comment) {
            if (!$this->isAllowed($comment->getText())) {
                $errors[] = $this->buildError($comment->getStartLine());
            }
        }

        return $errors;
    }

    /**
     * Checks whether a comment text is allowed.
     *
     * A comment passes when it carries no task marker, or when every line
     * containing a marker also matches the configured issue format.
     */
    private function isAllowed(string $text): bool
    {
        if (preg_match(self::MARKER_PATTERN, $text) !== 1) {
            return true;
        }

        $lines = preg_split('/\R/', $text);

        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            if (preg_match(self::MARKER_PATTERN, $line) === 1
                && preg_match($this->issueFormat, $line) !== 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Builds an error for a comment at the given line.
     *
     * @throws \PHPStan\ShouldNotHappenException
     */
    private function buildError(int $line): IdentifierRuleError
    {
        return RuleErrorBuilder::message(
            sprintf(
                "Unresolved TODO comment on line %d. Use '@todo #ISSUE description' format linked to an issue.",
                $line,
            ),
        )
            ->identifier('haspadar.todoComment')
            ->line($line)
            ->build();
    }
}
