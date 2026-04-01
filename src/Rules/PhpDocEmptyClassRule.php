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

/**
 * Checks that every class PHPDoc block contains a summary line.
 * Classes without a PHPDoc block are skipped. Blocks that exist
 * but contain only tags (no summary) or are completely empty trigger an error.
 *
 * @implements Rule<Class_>
 */
final readonly class PhpDocEmptyClassRule implements Rule
{
    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     * @throws \PHPStan\ShouldNotHappenException
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

        $className = $node->name !== null
            ? $node->name->toString()
            : 'anonymous class';

        return [
            RuleErrorBuilder::message(
                sprintf('PHPDoc for %s must contain a summary line.', $className),
            )
                ->identifier('haspadar.phpdocEmpty')
                ->build(),
        ];
    }
}
