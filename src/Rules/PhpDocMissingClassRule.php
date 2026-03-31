<?php

declare(strict_types=1);

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
    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassLike::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(
        Node $node,
        Scope $scope,
    ): array {
        /** @var ClassLike $node */
        if ($node instanceof Trait_) {
            return [];
        }

        if ($node instanceof Class_ && $node->isAnonymous()) {
            return [];
        }

        if ($node->getDocComment() !== null) {
            return [];
        }

        $kind = match (true) {
            $node instanceof Interface_ => 'interface',
            $node instanceof Enum_ => 'enum',
            default => 'class',
        };

        $name = $node->name !== null ? $node->name->toString() : '';

        return [
            RuleErrorBuilder::message(
                sprintf('PHPDoc is missing for %s %s.', $kind, $name),
            )
                ->identifier('haspadar.phpdocMissingClass')
                ->build(),
        ];
    }
}
