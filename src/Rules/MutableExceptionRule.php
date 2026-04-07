<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;
use Throwable;

/**
 * Reports non-readonly properties in exception classes.
 * A class is considered an exception if it implements \Throwable (checked via PHPStan ReflectionProvider).
 * Only own (non-inherited) properties are checked. Abstract and anonymous classes are excluded.
 *
 * @implements Rule<Class_>
 */
final readonly class MutableExceptionRule implements Rule
{
    /**
     * Constructs the rule with the given reflection provider.
     */
    public function __construct(private ReflectionProvider $reflectionProvider) {}

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
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
        /** @var Class_ $node */

        if ($node->isAbstract() || $node->isAnonymous() || $node->namespacedName === null) {
            return [];
        }

        $className = $node->namespacedName->toString();

        if (!$this->reflectionProvider->hasClass($className)
            || !$this->reflectionProvider->getClass($className)->implementsInterface(Throwable::class)
        ) {
            return [];
        }

        $errors = [];

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Property && !$stmt->isReadonly()) {
                $errors = [...$errors, ...$this->errorsForProperty($stmt)];
            }
        }

        return $errors;
    }

    /**
     * Returns errors for each non-readonly property declared in the given property node.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function errorsForProperty(Property $property): array
    {
        $errors = [];

        foreach ($property->props as $prop) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Exception property $%s must be readonly to prevent mutation after construction.',
                    $prop->name->toString(),
                ),
            )
                ->identifier('haspadar.mutableException')
                ->line($prop->getLine())
                ->build();
        }

        return $errors;
    }
}
