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
 * Checks that every method PHPDoc block in a concrete class contains a summary line.
 * Methods without a PHPDoc block are skipped. Blocks that exist but contain only
 * tags (no summary) or are completely empty trigger an error.
 * Methods in interfaces and traits are skipped.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class PhpDocEmptyMethodRule implements Rule
{
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

        if ($summary !== null || $docComment === null) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('PHPDoc for %s() must contain a summary line.', $node->name->toString()),
            )
                ->identifier('haspadar.phpdocEmpty')
                ->build(),
        ];
    }
}
