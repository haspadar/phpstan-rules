<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PHPStan\PhpDocParser\ParserConfig;
use PHPStan\ShouldNotHappenException;

/**
 * Parses PHPDoc blocks and checks whether tag descriptions start with a capital letter.
 * Shared by ReturnDescriptionCapitalRule and ParamDescriptionCapitalRule.
 * Uses PHPStan PhpDocParser to correctly handle generic types with spaces
 * (e.g. array<int, string>).
 */
final readonly class PhpDocDescriptionChecker
{
    private Lexer $lexer;

    private PhpDocParser $phpDocParser;

    /**
     * Constructs the checker and initialises the PHPStan PHPDoc lexer and parser.
     *
     * @throws ShouldNotHappenException
     */
    public function __construct()
    {
        $config = new ParserConfig([]);
        $constExprParser = new ConstExprParser($config);
        $typeParser = new TypeParser($config, $constExprParser);
        $this->lexer = new Lexer($config);
        $this->phpDocParser = new PhpDocParser($config, $typeParser, $constExprParser);
    }

    /**
     * Extracts the description text from the first @return tag, or null if absent or empty.
     *
     * @param string $docText Raw PHPDoc block text with the opening and closing delimiters.
     * @throws ShouldNotHappenException
     */
    public function extractReturnDescription(string $docText): ?string
    {
        $tokens = new TokenIterator($this->lexer->tokenize($docText));
        $phpDocNode = $this->phpDocParser->parse($tokens);

        foreach ($phpDocNode->getReturnTagValues() as $returnTag) {
            return $returnTag->description !== ''
                ? $returnTag->description
                : null;
        }

        return null;
    }

    /**
     * Returns all @param tag descriptions that are non-empty, keyed by parameter name.
     *
     * @param string $docText Raw PHPDoc block text with the opening and closing delimiters.
     * @throws ShouldNotHappenException
     * @return array<string, string>
     */
    public function extractParamDescriptions(string $docText): array
    {
        $tokens = new TokenIterator($this->lexer->tokenize($docText));
        $phpDocNode = $this->phpDocParser->parse($tokens);

        $descriptions = [];

        foreach ($phpDocNode->getParamTagValues() as $paramTag) {
            if ($paramTag->description !== '') {
                $descriptions[$paramTag->parameterName] = $paramTag->description;
            }
        }

        return $descriptions;
    }

    /**
     * Returns the parameter names (with leading `$`) documented by `@param` tags in the PHPDoc block.
     *
     * @param string $docText Raw PHPDoc block text with the opening and closing delimiters.
     * @throws ShouldNotHappenException
     * @return list<string>
     */
    public function extractParamNames(string $docText): array
    {
        $tokens = new TokenIterator($this->lexer->tokenize($docText));
        $phpDocNode = $this->phpDocParser->parse($tokens);

        $names = [];

        foreach ($phpDocNode->getParamTagValues() as $paramTag) {
            $names[] = $paramTag->parameterName;
        }

        return $names;
    }

    /**
     * Returns parameter names (with leading `$`) whose `@param` tag has an empty description.
     *
     * Each occurrence is reported independently, so duplicate parameter names with mixed
     * descriptions appear once per empty-description tag.
     *
     * @param string $docText Raw PHPDoc block text with the opening and closing delimiters.
     * @throws ShouldNotHappenException
     * @return list<string>
     */
    public function extractEmptyParamNames(string $docText): array
    {
        $tokens = new TokenIterator($this->lexer->tokenize($docText));
        $phpDocNode = $this->phpDocParser->parse($tokens);

        $empty = [];

        foreach ($phpDocNode->getParamTagValues() as $paramTag) {
            if ($paramTag->description === '' && $paramTag->parameterName !== '') {
                $empty[] = $paramTag->parameterName;
            }
        }

        return $empty;
    }

    /**
     * Returns true if the string starts with an uppercase Unicode letter.
     *
     * @param string $text Text to inspect.
     */
    public function startsWithCapital(string $text): bool
    {
        return preg_match('/^\p{Lu}/u', $text) === 1;
    }
}
