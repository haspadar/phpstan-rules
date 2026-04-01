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

/** @implements Rule<FileNode> */
final readonly class FileLengthRule implements Rule
{
    private bool $skipBlankLines;

    private bool $skipComments;

    /**
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

    /** @return list<IdentifierRuleError> */
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
        $result = file($scope->getFile(), FILE_IGNORE_NEW_LINES);
        $allLines = $result === false
            ? []
            : $result;

        return count($this->countableLines($allLines));
    }

    private function shouldSkipBlankLine(string $line): bool
    {
        return $this->skipBlankLines && trim($line) === '';
    }

    /**
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

    /** @return array{bool, bool} */
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
