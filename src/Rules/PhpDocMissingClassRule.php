<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Checks that every named class has a PHPDoc comment.
 * Anonymous classes are skipped — they are typically used for stubs in tests.
 * Interfaces and traits use different node types and are not checked by this rule.
 *
 * @implements Rule<Class_>
 */
final readonly class PhpDocMissingClassRule implements Rule
{
    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
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
        /** @var Class_ $node */
        if ($node->isAnonymous() || $node->getDocComment() !== null) {
            return [];
        }

        $name = $node->name !== null ? $node->name->toString() : '';

        return [
            RuleErrorBuilder::message(
                sprintf('PHPDoc is missing for class %s.', $name),
            )
                ->identifier('haspadar.phpdocMissingClass')
                ->build(),
        ];
    }
}
