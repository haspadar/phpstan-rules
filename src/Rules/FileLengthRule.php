<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\FileNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports a file that exceeds the configured maximum line count.
 *
 * @implements Rule<FileNode>
 */
final readonly class FileLengthRule implements Rule
{
    private bool $skipBlankLines;

    private bool $skipComments;

    /**
     * Constructs the rule with the given line limit and filtering options.
     *
     * @param array{
     *     skipBlankLines?: bool,
     *     skipComments?: bool
     * } $options
     */
    public function __construct(private int $maxLines = 1000, array $options = [])
    {
        $this->skipBlankLines = $options['skipBlankLines'] ?? false;
        $this->skipComments = $options['skipComments'] ?? false;
    }

    #[Override]
    public function getNodeType(): string
    {
        return FileNode::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @throws ShouldNotHappenException
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

    /**
     * Returns the number of countable lines in the file.
     *
     * @throws ShouldNotHappenException
     */
    private function lineCount(Scope $scope): int
    {
        $allLines = file($scope->getFile(), FILE_IGNORE_NEW_LINES);

        if ($allLines === false) {
            throw new ShouldNotHappenException();
        }

        return count($this->countableLines($allLines));
    }

    private function shouldSkipBlankLine(string $line): bool
    {
        return $this->skipBlankLines && trim($line) === '';
    }

    /**
     * Filters the given lines to those that should be counted toward the limit.
     *
     * @param list<string> $allLines
     * @return array<int, string>
     */
    private function countableLines(array $allLines): array
    {
        $result = [];
        $inBlockComment = false;

        foreach ($allLines as $index => $line) {
            [$skip, $inBlockComment] = $this->shouldSkip($line, $inBlockComment);

            if (!$skip) {
                $result[$index] = $line;
            }
        }

        return $result;
    }

    /**
     * Returns whether the given line should be skipped and the updated block-comment state.
     *
     * @return array{bool, bool}
     */
    private function shouldSkip(string $line, bool $inBlockComment): array
    {
        if ($inBlockComment) {
            return [$this->skipComments, !str_contains($line, '*/')];
        }

        if ($this->skipComments && preg_match('(^\s*(//|#|/\*))', $line) === 1) {
            return [true, preg_match('(^\s*/\*)', $line) === 1 && !str_contains($line, '*/')];
        }

        return [$this->shouldSkipBlankLine($line), false];
    }
}
