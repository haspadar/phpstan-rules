<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/** @implements Rule<ClassMethod> */
final class MethodLinesRule implements Rule
{
    private readonly int $maxLines;

    private readonly bool $skipBlankLines;

    private readonly bool $skipComments;

    /**
     * @param array{
     *     maxLines?: int,
     *     skipBlankLines?: bool,
     *     skipComments?: bool
     * } $options
     */
    public function __construct(array $options = [])
    {
        $this->maxLines = $options['maxLines'] ?? 50;
        $this->skipBlankLines = $options['skipBlankLines'] ?? false;
        $this->skipComments = $options['skipComments'] ?? false;
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @throws ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
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
        if (!$this->shouldReadSourceLines()) {
            return $node->getEndLine() - $node->getStartLine() + 1;
        }

        $allLines = file($scope->getFile(), FILE_IGNORE_NEW_LINES);
        if (!is_array($allLines)) {
            return $node->getEndLine() - $node->getStartLine() + 1;
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

    private function shouldReadSourceLines(): bool
    {
        return $this->skipBlankLines || $this->skipComments;
    }

    /**
     * @param list<string> $methodLines
     *
     * @return list<string>
     */
    private function countableLines(array $methodLines): array
    {
        return array_values(array_filter($methodLines, fn(string $line) => $this->isCountable($line)));
    }
}
