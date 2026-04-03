<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that every public method in a class has a PHPDoc comment.
 * Non-public methods are skipped when checkPublicOnly is true (default).
 * Methods with the #[Override] attribute are skipped when skipOverridden is true (default).
 * Methods in interfaces and traits are always skipped.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class PhpDocMissingMethodRule implements Rule
{
    private bool $checkPublicOnly;

    private bool $skipOverridden;

    /**
     * Constructs the rule with the given visibility and override options.
     *
     * @param array{checkPublicOnly?: bool, skipOverridden?: bool} $options
     */
    public function __construct(array $options = [])
    {
        $this->checkPublicOnly = $options['checkPublicOnly'] ?? true;
        $this->skipOverridden = $options['skipOverridden'] ?? true;
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $scope->getClassReflection();

        /** @var ClassMethod $node */
        if (
            $reflection === null
            || !$reflection->isClass()
            || ($this->checkPublicOnly && !$node->isPublic())
            || ($this->skipOverridden && $this->hasOverrideAttribute($node))
            || $node->getDocComment() !== null
        ) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf('PHPDoc is missing for method %s().', $node->name->toString()),
            )
                ->identifier('haspadar.phpdocMissingMethod')
                ->build(),
        ];
    }

    /**
     * Returns true if the method has the #[Override] attribute.
     */
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
