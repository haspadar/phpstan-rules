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
use PHPStan\ShouldNotHappenException;

/**
 * Reports a class method that exceeds the configured maximum line count.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class MethodLengthRule implements Rule
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
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
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

    /**
     * Returns the number of countable lines in the method.
     *
     * @throws ShouldNotHappenException
     */
    private function lineCount(ClassMethod $node, Scope $scope): int
    {
        $allLines = file($scope->getFile(), FILE_IGNORE_NEW_LINES);

        if ($allLines === false) {
            throw new ShouldNotHappenException();
        }

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
     * Filters the given lines to those that should be counted toward the limit.
     *
     * @param list<string> $methodLines
     * @return array<int, string>
     */
    private function countableLines(array $methodLines): array
    {
        return array_filter($methodLines, fn(string $line) => $this->isCountable($line));
    }
}
