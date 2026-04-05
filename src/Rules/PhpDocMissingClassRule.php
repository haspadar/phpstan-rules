<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Checks that every named class, interface, and enum has a PHPDoc comment.
 * Anonymous classes are skipped — they are typically used for stubs in tests.
 * Traits are skipped — trait documentation conventions differ.
 * Reports identifier `haspadar.phpdocMissingClass` for all matching node types.
 *
 * @implements Rule<ClassLike>
 */
final readonly class PhpDocMissingClassRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassLike::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassLike $node */
        if ($node instanceof Trait_
            || ($node instanceof Class_ && $node->isAnonymous())
            || $node->getDocComment() !== null
        ) {
            return [];
        }

        $kind = match (true) {
            $node instanceof Interface_ => 'interface',
            $node instanceof Enum_ => 'enum',
            default => 'class',
        };

        $name = $node->name?->toString() ?? 'anonymous';

        return [
            RuleErrorBuilder::message(
                sprintf('PHPDoc is missing for %s %s.', $kind, $name),
            )
                ->identifier('haspadar.phpdocMissingClass')
                ->build(),
        ];
    }
}
