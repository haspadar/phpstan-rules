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
 * Checks that every class PHPDoc block contains a summary line.
 * Classes without a PHPDoc block are skipped. Blocks that exist
 * but contain only tags (no summary) or are completely empty trigger an error.
 *
 * @implements Rule<Class_>
 */
final readonly class PhpDocEmptyClassRule implements Rule
{
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

        if ($docComment === null) {
            return [];
        }

        $summary = SummaryExtractor::extract($docComment->getText());

        if ($summary !== null) {
            return [];
        }

        if ($node->name === null) {
            throw new ShouldNotHappenException();
        }

        $className = $node->name->toString();

        return [
            RuleErrorBuilder::message(
                sprintf('PHPDoc for %s must contain a summary line.', $className),
            )
                ->identifier('haspadar.phpdocEmpty')
                ->build(),
        ];
    }
}
