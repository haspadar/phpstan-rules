<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\PhpDoc\SummaryExtractor;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Checks that the PHPDoc summary line of every class method ends with
 * a period, question mark, or exclamation mark, and optionally starts with
 * a capital letter. Methods without a PHPDoc block are skipped. Blocks
 * containing only tags (no summary) are skipped. Methods in interfaces
 * and traits are skipped.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class PhpDocPunctuationMethodRule implements Rule
{
    private bool $checkCapitalization;

    /** @param array{checkCapitalization?: bool} $options */
    public function __construct(array $options = [])
    {
        $this->checkCapitalization = $options['checkCapitalization'] ?? true;
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-param ClassMethod $node
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $scope->getClassReflection();

        if ($reflection === null || !$reflection->isClass()) {
            return [];
        }

        $docComment = $node->getDocComment();
        $summary = $docComment !== null
            ? SummaryExtractor::extract($docComment->getText())
            : null;

        if ($summary === null) {
            return [];
        }

        $methodName = $node->name->toString();
        $errors = [];

        if (!$this->endsWithPunctuation($summary)) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'PHPDoc summary for %s() must end with a period, question mark, or exclamation mark.',
                    $methodName,
                ),
            )
                ->identifier('haspadar.phpdocPunctuation')
                ->build();
        }

        if ($this->checkCapitalization && !$this->startsWithCapital($summary)) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('PHPDoc summary for %s() must start with a capital letter.', $methodName),
            )
                ->identifier('haspadar.phpdocStyle')
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the string ends with `.`, `?`, or `!`
     */
    private function endsWithPunctuation(string $text): bool
    {
        return str_ends_with($text, '.')
            || str_ends_with($text, '?')
            || str_ends_with($text, '!');
    }

    /**
     * Returns true if the string starts with an uppercase letter
     */
    private function startsWithCapital(string $text): bool
    {
        return $text !== '' && mb_strtoupper(mb_substr($text, 0, 1)) === mb_substr($text, 0, 1) && ctype_alpha(
            $text[0],
        );
    }
}
