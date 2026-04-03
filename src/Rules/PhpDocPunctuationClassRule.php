<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\PhpDoc\SummaryExtractor;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that the PHPDoc summary line of every class declaration ends with proper punctuation.
 *
 * Optionally also checks that the summary starts with a capital letter.
 * Classes without a PHPDoc block are skipped. Blocks containing only tags (no summary) are skipped.
 *
 * @implements Rule<Class_>
 */
final readonly class PhpDocPunctuationClassRule implements Rule
{
    private bool $checkCapitalization;

    /**
     * Constructs the rule with the given capitalization option.
     *
     * @param array{checkCapitalization?: bool} $options
     */
    public function __construct(array $options = [])
    {
        $this->checkCapitalization = $options['checkCapitalization'] ?? true;
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
        $docComment = $node->getDocComment();
        $summary = $docComment !== null
            ? SummaryExtractor::extract($docComment->getText())
            : null;

        if ($summary === null) {
            return [];
        }

        $className = $node->name !== null
            ? $node->name->toString()
            : 'anonymous class';
        $errors = [];

        if (!$this->endsWithPunctuation($summary)) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'PHPDoc summary for %s must end with a period, question mark, or exclamation mark.',
                    $className,
                ),
            )
                ->identifier('haspadar.phpdocPunctuation')
                ->build();
        }

        if ($this->checkCapitalization && !$this->startsWithCapital($summary)) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('PHPDoc summary for %s must start with a capital letter.', $className),
            )
                ->identifier('haspadar.phpdocStyle')
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the string ends with `.`, `?`, or `!`.
     */
    private function endsWithPunctuation(string $text): bool
    {
        return str_ends_with($text, '.')
            || str_ends_with($text, '?')
            || str_ends_with($text, '!');
    }

    /**
     * Returns true if the string starts with an uppercase letter.
     */
    private function startsWithCapital(string $text): bool
    {
        return $text !== '' && mb_strtoupper(mb_substr($text, 0, 1)) === mb_substr($text, 0, 1) && ctype_alpha(
            $text[0],
        );
    }
}
