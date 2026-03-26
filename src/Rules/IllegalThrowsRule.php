<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects methods whose @throws PHPDoc tags declare overly broad exception types.
 * Reads @throws tags from PHPDoc using PHPStan's phpdoc-parser, extracts the short
 * class name (last segment after backslash), and reports any name found in the
 * configured illegal list. Methods marked with #[Override] are skipped by default
 * because they do not control the parent's declared contract.
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
     */
    public function __construct(
        array $illegalClassNames = ['Error', 'RuntimeException', 'Throwable'],
        bool $ignoreOverriddenMethods = true,
    ) {
        $this->illegalClassNames = $illegalClassNames;
        $this->ignoreOverriddenMethods = $ignoreOverriddenMethods;
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
     * Parses @throws tags from a PHPDoc string and returns a map of short class name → absolute line number
     *
     * @return array<string, int>
     */
    private function parseThrowsTags(string $docComment, int $docStartLine): array
    {
        $config = new ParserConfig(['lines' => true]);
        $lexer = new Lexer($config);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);

        $tokens = new TokenIterator($lexer->tokenize($docComment));
        $phpDocNode = $phpDocParser->parse($tokens);

        $result = [];

        foreach ($phpDocNode->getTagsByName('@throws') as $tag) {
            $value = $tag->value;

            if (!$value instanceof ThrowsTagValueNode) {
                continue;
            }

            $typeName = ltrim((string) $value->type, '\\');
            $parts = explode('\\', $typeName);
            $shortName = $parts[count($parts) - 1];

            if ($shortName === '') {
                continue;
            }

            /** @var mixed $tagRelativeLine */
            $tagRelativeLine = $tag->getAttribute('startLine');
            $result[$shortName] = is_int($tagRelativeLine) ? $docStartLine + $tagRelativeLine - 1 : $docStartLine;
        }

        return $result;
    }
}
