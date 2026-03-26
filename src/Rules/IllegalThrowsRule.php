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
 * Scans PHPDoc text with a regex to find @throws lines, parses union and intersection
 * types, and compares each fully-qualified name against the configured illegal list.
 * Methods marked with #[Override] are skipped by default because they do not control
 * the parent's declared contract.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class IllegalThrowsRule implements Rule
{
    /** @var list<string> */
    private array $illegalClassNames;

    private bool $ignoreOverriddenMethods;

    /**
     * @param list<string> $illegalClassNames Class names (with or without leading backslash) that are forbidden in @throws
     * @param array{ignoreOverriddenMethods?: bool} $options
     */
    public function __construct(
        array $illegalClassNames = ['Error', 'RuntimeException', 'Throwable'],
        array $options = [],
    ) {
        $this->illegalClassNames = array_map(
            static fn(string $name): string => ltrim($name, '\\'),
            $illegalClassNames,
        );
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

        foreach ($this->parseThrowsTags($docComment->getText(), $docComment->getStartLine()) as $throwTag) {
            $typeName = $throwTag['typeName'];
            $line = $throwTag['line'];

            if (!in_array($typeName, $this->illegalClassNames, true)) {
                continue;
            }

            $parts = explode('\\', $typeName);
            $shortName = $parts[count($parts) - 1];

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
     * Scans PHPDoc text for @throws lines and returns every declared type with its absolute line number
     *
     * Handles union types (A|B), leading backslashes, and repeated @throws tags independently.
     *
     * @return list<array{typeName: string, line: int}>
     */
    private function parseThrowsTags(string $docComment, int $docStartLine): array
    {
        $lines = explode("\n", $docComment);
        $result = [];

        foreach ($lines as $offset => $line) {
            if (preg_match('/@throws\s+(\S+)/', $line, $matches) !== 1) {
                continue;
            }

            $rawTypes = $matches[1];

            foreach (preg_split('/[|&]/', $rawTypes) ?: [] as $rawType) {
                $typeName = ltrim(trim($rawType), '\\?');

                if ($typeName === '') {
                    continue;
                }

                $result[] = [
                    'typeName' => $typeName,
                    'line' => $docStartLine + $offset,
                ];
            }
        }

        return $result;
    }
}
