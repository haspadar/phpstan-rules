<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports a class that exceeds the configured maximum line count.
 *
 * Counts all lines between the opening and closing braces of a class.
 * Optionally skips blank lines and comment lines.
 *
 * @implements Rule<Class_>
 */
final readonly class ClassLengthRule implements Rule
{
    private bool $skipBlankLines;

    private bool $skipComments;

    /**
     * Constructs the rule with the given line limit and filtering options.
     *
     * @param int $maxLines Maximum physical line count per class body.
     * @param array{
     *     skipBlankLines?: bool,
     *     skipComments?: bool
     * } $options
     */
    public function __construct(private int $maxLines = 500, array $options = [])
    {
        $this->skipBlankLines = $options['skipBlankLines'] ?? false;
        $this->skipComments = $options['skipComments'] ?? false;
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
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

        if ($node->name === null) {
            throw new ShouldNotHappenException();
        }

        $className = $node->name->toString();

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Class %s is %d lines long. Maximum allowed is %d.',
                    $className,
                    $lines,
                    $this->maxLines,
                ),
            )
                ->identifier('haspadar.classLength')
                ->build(),
        ];
    }

    /**
     * Returns the number of countable lines in the class body.
     *
     * @throws ShouldNotHappenException
     */
    private function lineCount(Class_ $node, Scope $scope): int
    {
        $allLines = file($scope->getFile(), FILE_IGNORE_NEW_LINES);

        if ($allLines === false) {
            throw new ShouldNotHappenException();
        }

        $classLines = array_slice(
            $allLines,
            $node->getStartLine() - 1,
            $node->getEndLine() - $node->getStartLine() + 1,
        );

        return count($this->countableLines($classLines));
    }

    /**
     * Filters the given lines to those that should be counted toward the limit.
     *
     * @param list<string> $classLines
     * @return array<int, string>
     */
    private function countableLines(array $classLines): array
    {
        $result = [];
        $inBlockComment = false;

        foreach ($classLines as $index => $line) {
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

    private function shouldSkipBlankLine(string $line): bool
    {
        return $this->skipBlankLines && trim($line) === '';
    }
}
