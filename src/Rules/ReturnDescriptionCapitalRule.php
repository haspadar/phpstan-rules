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

/**
 * Checks that the description of the @return PHPDoc tag in every class method
 * starts with a capital letter. Methods without a PHPDoc block, @return tags
 * without a description, and methods in interfaces and traits are skipped.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ReturnDescriptionCapitalRule implements Rule
{
    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $scope->getClassReflection();

        /** @var ClassMethod $node */
        $docComment = $node->getDocComment();

        if ($reflection === null || !$reflection->isClass() || $docComment === null) {
            return [];
        }

        $description = $this->extractReturnDescription($docComment->getText());

        if ($description === null) {
            return [];
        }

        if ($this->startsWithCapital($description)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    '@return description for %s() must start with a capital letter.',
                    $node->name->toString(),
                ),
            )
                ->identifier('haspadar.returnCapital')
                ->build(),
        ];
    }

    /**
     * Extracts the description text from @return tag, or null if absent or no description
     */
    private function extractReturnDescription(string $docText): ?string
    {
        if (preg_match('/^\s*\*\s*@return\s+\S+\s+([^*\s].*)$/m', $docText, $matches) !== 1) {
            return null;
        }

        return trim($matches[1]);
    }

    /**
     * Returns true if the string starts with an uppercase letter
     */
    private function startsWithCapital(string $text): bool
    {
        return $text !== '' && ctype_alpha($text[0]) && mb_strtoupper(mb_substr($text, 0, 1)) === mb_substr($text, 0, 1);
    }
}
