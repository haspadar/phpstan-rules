<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that every public property in a class has a PHPDoc comment.
 * Non-public properties are skipped when checkPublicOnly is true (default).
 * Promoted constructor parameters are never checked — they are Param nodes, not Property nodes.
 *
 * @implements Rule<Property>
 */
final readonly class PhpDocMissingPropertyRule implements Rule
{
    private bool $checkPublicOnly;

    /**
     * Constructs the rule with the given visibility options.
     *
     * @param array{checkPublicOnly?: bool} $options
     */
    public function __construct(array $options = [])
    {
        $this->checkPublicOnly = $options['checkPublicOnly'] ?? true;
    }

    #[Override]
    public function getNodeType(): string
    {
        return Property::class;
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

        /** @var Property $node */
        if (
            $reflection === null
            || !$reflection->isClass()
            || ($this->checkPublicOnly && !$node->isPublic())
            || $node->getDocComment() !== null
        ) {
            return [];
        }

        $errors = [];

        foreach ($node->props as $prop) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('PHPDoc is missing for property $%s.', $prop->name->toString()),
            )
                ->identifier('haspadar.phpdocMissingProperty')
                ->build();
        }

        return $errors;
    }
}
