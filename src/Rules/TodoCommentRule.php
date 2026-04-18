<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use InvalidArgumentException;
use Override;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Forbids task-tracking comments unless they follow the puzzle-driven format linked to an issue.
 *
 * Scans every comment attached to a node inside a ClassMethod body and the
 * method's own PHPDoc for generic task markers (TODO, FIXME, XXX) in any case.
 * A comment containing such a marker is allowed only when every line carrying
 * a marker matches the configured issue format (default: `@todo #NUMBER ...`)
 * and leaves no extra marker on the same line once the allowed match is removed.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class TodoCommentRule implements Rule
{
    private const string MARKER_PATTERN = '/\b(?:TODO|FIXME|XXX)\b/i';

    /** @var non-empty-string */
    private string $issueFormat;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{
     *     issueFormat?: non-empty-string
     * } $options
     * @throws InvalidArgumentException when `issueFormat` is not a valid regex
     */
    public function __construct(array $options = [])
    {
        $format = $options['issueFormat'] ?? '/@todo\s+#\d+\b/i';

        if (@preg_match($format, '') === false) {
            throw new InvalidArgumentException(
                sprintf("Invalid 'issueFormat' regex: %s", $format),
            );
        }

        $this->issueFormat = $format;
    }

    /**
     * Returns the AST node type this rule handles.
     */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @param ClassMethod $node
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
     * @param Node $node AST node whose attached comments are inspected
     * @throws ShouldNotHappenException
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
     * containing a marker matches the configured issue format and contains
     * no further marker once the allowed match is stripped.
     *
     * @param string $text Raw comment text including delimiters
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
            if (preg_match(self::MARKER_PATTERN, $line) !== 1) {
                continue;
            }

            if (preg_match($this->issueFormat, $line) !== 1) {
                return false;
            }

            $stripped = preg_replace($this->issueFormat, '', $line);

            if ($stripped === null || preg_match(self::MARKER_PATTERN, $stripped) === 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Builds an error for a comment at the given line.
     *
     * @param int $line One-based line number where the offending comment starts
     * @throws ShouldNotHappenException
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
