<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/** @implements Rule<ClassMethod> */
final readonly class MethodLengthRule implements Rule
{
    private bool $skipBlankLines;

    private bool $skipComments;

    /**
     * @param array{
     *     skipBlankLines?: bool,
     *     skipComments?: bool
     * } $options
     */
    public function __construct(private int $maxLines = 100, array $options = [])
    {
        $this->skipBlankLines = $options['skipBlankLines'] ?? false;
        $this->skipComments = $options['skipComments'] ?? false;
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-param ClassMethod $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $lines = $this->lineCount($node, $scope);

        if ($lines <= $this->maxLines) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s() is %d lines long. Maximum allowed is %d.',
                    $node->name->toString(),
                    $lines,
                    $this->maxLines,
                ),
            )
                ->identifier('haspadar.methodLines')
                ->build(),
        ];
    }

    private function lineCount(ClassMethod $node, Scope $scope): int
    {
        $result = file($scope->getFile(), FILE_IGNORE_NEW_LINES);
        $allLines = $result === false
            ? []
            : $result;

        $methodLines = array_slice(
            $allLines,
            $node->getStartLine() - 1,
            $node->getEndLine() - $node->getStartLine() + 1,
        );

        return count($this->countableLines($methodLines));
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
     * @param list<string> $methodLines
     * @return array<int, string>
     */
    private function countableLines(array $methodLines): array
    {
        return array_filter($methodLines, fn(string $line) => $this->isCountable($line));
    }
}
