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
use Throwable;

/**
 * Reports non-readonly properties in exception classes. A class is considered an exception
 * if it implements \Throwable (checked via PHPStan ReflectionProvider using the fully
 * qualified class name from the AST node). Only own (non-inherited) properties are checked.
 * Abstract and anonymous classes are excluded. Readonly class modifier cannot be used
 * because RuntimeException and Exception are not readonly, so each property must be
 * declared readonly individually.
 *
 * @implements Rule<Class_>
 */
final readonly class MutableExceptionRule implements Rule
{
    /** @param ReflectionProvider $reflectionProvider */
    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {}

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
        if ($node->isAbstract() || $node->isAnonymous() || $node->namespacedName === null) { // @codeCoverageIgnore
            return [];
        }

        $className = $node->namespacedName->toString();

        if (!$this->reflectionProvider->hasClass($className)
            || !$this->reflectionProvider->getClass($className)->implementsInterface(Throwable::class)
        ) {
            return []; // @codeCoverageIgnore
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
     * @param Property $property
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
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
