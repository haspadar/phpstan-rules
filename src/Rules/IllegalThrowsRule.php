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
 * Detects methods whose @throws PHPDoc tags declare overly broad exception types.
 * Scans PHPDoc text with a regex to find @throws lines, extracts the short class
 * name (last segment after backslash), and reports any name found in the configured
 * illegal list. Methods marked with #[Override] are skipped by default because they
 * do not control the parent's declared contract.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class IllegalThrowsRule implements Rule
{
    /** @var list<string> */
    private array $illegalClassNames;

    private bool $ignoreOverriddenMethods;

    /**
     * @param list<string> $illegalClassNames Short class names (without leading backslash) that are forbidden in @throws
     * @param array{ignoreOverriddenMethods?: bool} $options
     */
    public function __construct(
        array $illegalClassNames = ['Error', 'RuntimeException', 'Throwable'],
        array $options = [],
    ) {
        $this->illegalClassNames = $illegalClassNames;
        $this->ignoreOverriddenMethods = $options['ignoreOverriddenMethods'] ?? true;
    }

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
        /** @var ClassMethod $node */
        if ($this->ignoreOverriddenMethods && $this->isOverridden($node)) {
            return [];
        }

        $docComment = $node->getDocComment();

        if ($docComment === null) {
            return [];
        }

        $errors = [];

        foreach ($this->parseThrowsTags($docComment->getText(), $docComment->getStartLine()) as $shortName => $line) {
            if (!in_array($shortName, $this->illegalClassNames, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Throwing %s is not allowed.', $shortName),
            )
                ->identifier('haspadar.illegalThrows')
                ->line($line)
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the method has a #[Override] attribute
     */
    private function isOverridden(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($attr->name->getLast() === 'Override') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Scans PHPDoc text for @throws lines and returns a map of short class name → absolute line number
     *
     * @return array<string, int>
     */
    private function parseThrowsTags(string $docComment, int $docStartLine): array
    {
        $lines = explode("\n", $docComment);
        $result = [];

        foreach ($lines as $offset => $line) {
            if (preg_match('/@throws\s+([\\\\\w]+)/', $line, $matches) !== 1) {
                continue;
            }

            $typeName = ltrim($matches[1], '\\');
            $parts = explode('\\', $typeName);
            $shortName = $parts[count($parts) - 1];

            if ($shortName === '') {
                continue;
            }

            $result[$shortName] = $docStartLine + $offset;
        }

        return $result;
    }
}
