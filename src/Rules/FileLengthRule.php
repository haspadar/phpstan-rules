<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\FileNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<FileNode> */
final readonly class FileLengthRule implements Rule
{
    private int $maxLines;

    private bool $skipBlankLines;

    private bool $skipComments;

    /**
     * @param array{
     *     skipBlankLines?: bool,
     *     skipComments?: bool
     * } $options
     */
    public function __construct(int $maxLines = 100, array $options = [])
    {
        $this->maxLines = $maxLines;
        $this->skipBlankLines = $options['skipBlankLines'] ?? false;
        $this->skipComments = $options['skipComments'] ?? false;
    }

    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return FileNode::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $lines = $this->lineCount($scope);

        if ($lines <= $this->maxLines) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'File %s is %d lines long. Maximum allowed is %d.',
                    basename($scope->getFile()),
                    $lines,
                    $this->maxLines,
                ),
            )
                ->identifier('haspadar.fileLines')
                ->build(),
        ];
    }

    private function lineCount(Scope $scope): int
    {
        $allLines = file($scope->getFile(), FILE_IGNORE_NEW_LINES);
        if (!is_array($allLines)) {
            return 0;
        }

        return count($this->countableLines($allLines));
    }

    private function isCountable(string $line): bool
    {
        return !$this->shouldSkipBlankLine($line) && !$this->shouldSkipCommentLine($line);
    }

    private function shouldSkipBlankLine(string $line): bool
    {
        return $this->skipBlankLines && trim($line) === '';
    }

    private function shouldSkipCommentLine(string $line): bool
    {
        return $this->skipComments && preg_match('(^\s*(//|/\*|\*|\*/|#))', $line) === 1;
    }

    /**
     * @param list<string> $allLines
     *
     * @return array<int, string>
     */
    private function countableLines(array $allLines): array
    {
        return array_filter($allLines, fn(string $line) => $this->isCountable($line));
    }
}
