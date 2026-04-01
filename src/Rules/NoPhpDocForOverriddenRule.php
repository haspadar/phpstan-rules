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
 * Checks that overridden methods do not have a PHPDoc comment.
 * Overridden methods are detected by the presence of the #[Override] attribute.
 * A method is considered overridden when it carries the #[Override] attribute (PHP 8.3+).
 * Documentation is inherited from the parent declaration and must not be duplicated.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NoPhpDocForOverriddenRule implements Rule
{
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
    public function processNode(
        Node $node,
        Scope $scope,
    ): array {
        /** @var ClassMethod $node */
        if (!$this->hasOverrideAttribute($node) || $node->getDocComment() === null) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Overridden method %s() must not have a PHPDoc comment.',
                    $node->name->toString(),
                ),
            )
                ->identifier('haspadar.noPhpdocOverride')
                ->build(),
        ];
    }

    /** Returns true if the method has the #[Override] attribute */
    private function hasOverrideAttribute(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if (in_array($attr->name->toString(), ['Override', '\Override'], true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
