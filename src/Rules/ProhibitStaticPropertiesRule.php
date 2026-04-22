<?php

declare(strict_types=1);

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
 * Detects static property declarations and reports an error for each one regardless of visibility.
 * Static properties are class-level mutable shared state that cannot be injected, are impossible to
 * isolate in tests, and create hidden links between all instances of the class. Accesses are not
 * checked separately: without a declaration, own accesses are syntactically impossible, and
 * accesses to third-party static fields (e.g., Carbon facades) are out of this rule's scope.
 *
 * @implements Rule<Property>
 */
final readonly class ProhibitStaticPropertiesRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Property::class;
    }

    /**
     * Reports an error for every declared property inside a static declaration.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Property $node */
        if (!$node->isStatic()) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection === null || $reflection->isAnonymous()
            ? 'class@anonymous'
            : $reflection->getName();

        $errors = [];

        foreach ($node->props as $prop) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Property %s::$%s is static. Static properties are prohibited.',
                    $className,
                    $prop->name->toString(),
                ),
            )
                ->identifier('haspadar.staticProperty')
                ->build();
        }

        return $errors;
    }
}
