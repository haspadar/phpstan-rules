<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
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
 * Checks that the description of the @return PHPDoc tag in every class method
 * starts with a capital letter. Methods without a PHPDoc block, @return tags
 * without a description, and methods in interfaces and traits are skipped.
 * Uses PHPStan PhpDocParser to correctly handle generic types with spaces
 * (e.g. array<int, string>).
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ReturnDescriptionCapitalRule implements Rule
{
    private readonly Lexer $lexer;

    private readonly PhpDocParser $phpDocParser;

    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function __construct()
    {
        $config = new ParserConfig([]);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $this->lexer = new Lexer($config);
        $this->phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
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
        $reflection = $scope->getClassReflection();

        /** @var ClassMethod $node */
        $docComment = $node->getDocComment();

        if ($reflection === null || !$reflection->isClass() || $docComment === null) {
            return [];
        }

        $description = $this->extractReturnDescription($docComment->getText());

        if ($description === null || $this->startsWithCapital($description)) {
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
     * Extracts the description text from @return tag using PhpDocParser,
     * or returns null if the tag is absent or has no description
     *
     * @throws \PHPStan\ShouldNotHappenException
     */
    private function extractReturnDescription(string $docText): ?string
    {
        $tokens = new TokenIterator($this->lexer->tokenize($docText));
        $phpDocNode = $this->phpDocParser->parse($tokens);

        foreach ($phpDocNode->getReturnTagValues() as $returnTag) {
            return $returnTag->description !== '' ? $returnTag->description : null;
        }

        return null;
    }

    /**
     * Returns true if the string starts with an uppercase Unicode letter
     */
    private function startsWithCapital(string $text): bool
    {
        $firstChar = mb_substr($text, 0, 1);

        return $firstChar !== '' && preg_match('/^\p{L}/u', $firstChar) === 1 && mb_strtoupper($firstChar) === $firstChar;
    }
}
