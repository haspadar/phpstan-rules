<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\RequireIgnoreReasonRule\SuppressViolationFinder;
use Override;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Node\FileNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Requires every suppress annotation to include a justification reason.
 *
 * Supported forms: PHPStan "at-phpstan-ignore identifier (reason)" and its
 * next-line and line variants (native parenthesised reason, PHPStan 1.11+);
 * Psalm "at-psalm-suppress Identifier -- reason" where the "--" delimiter is
 * borrowed from the ESLint convention because Psalm has no native reason field.
 *
 * A suppress without a reason, or with a reason shorter than `minReasonLength`,
 * is reported. Identifiers listed in `allowedBareIdentifiers` may appear without
 * a reason (useful for project-wide suppressions that are self-evident).
 *
 * @implements Rule<FileNode>
 */
final readonly class RequireIgnoreReasonRule implements Rule
{
    private const int DEFAULT_MIN_REASON_LENGTH = 5;

    private SuppressViolationFinder $finder;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{
     *     minReasonLength?: int,
     *     allowedBareIdentifiers?: list<string>
     * } $options Minimum reason length and a whitelist of identifiers that may omit the reason
     */
    public function __construct(array $options = [])
    {
        $this->finder = new SuppressViolationFinder(
            $options['minReasonLength'] ?? self::DEFAULT_MIN_REASON_LENGTH,
            $options['allowedBareIdentifiers'] ?? [],
        );
    }

    #[Override]
    public function getNodeType(): string
    {
        return FileNode::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param FileNode $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        foreach ($this->collectComments($node) as $comment) {
            foreach ($this->finder->find($comment->getText()) as $violation) {
                $errors[] = $this->buildError($violation, $comment->getStartLine());
            }
        }

        return $errors;
    }

    /**
     * Collects every PhpParser comment attached to any descendant node of the file.
     *
     * @return list<Comment>
     */
    private function collectComments(FileNode $node): array
    {
        $nodeFinder = new NodeFinder();
        $seen = [];
        $result = [];

        foreach ($nodeFinder->findInstanceOf($node->getNodes(), Node::class) as $descendant) {
            foreach ($descendant->getComments() as $comment) {
                $key = sprintf('%d:%d', $comment->getStartLine(), $comment->getStartFilePos());

                if (array_key_exists($key, $seen)) {
                    continue;
                }

                $seen[$key] = true;
                $result[] = $comment;
            }
        }

        return $result;
    }

    /**
     * Builds a PHPStan error message for a violation.
     *
     * @param array{identifier: string, offsetLine: int, kind: string} $violation
     * @throws ShouldNotHappenException
     */
    private function buildError(array $violation, int $baseLine): IdentifierRuleError
    {
        $identifier = $violation['identifier'];
        $message = $violation['kind'] === 'phpstan'
            ? sprintf(
                'Suppress "%s" must include a reason in parentheses: @phpstan-ignore %s (reason).',
                $identifier,
                $identifier,
            )
            : sprintf(
                'Suppress "%s" must include a reason after "--": @psalm-suppress %s -- reason.',
                $identifier,
                $identifier,
            );

        return RuleErrorBuilder::message($message)
            ->identifier('haspadar.requireIgnoreReason')
            ->line($baseLine + $violation['offsetLine'])
            ->build();
    }
}
