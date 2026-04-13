<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Enforces immutability by requiring all non-static properties to be readonly.
 *
 * Checks every concrete (non-abstract, non-anonymous) class and reports
 * properties that lack the readonly modifier. Classes declared as
 * readonly (PHP 8.2+) are automatically exempt, as all their properties
 * inherit the readonly modifier. Static properties are skipped because
 * PHP does not allow readonly on static properties.
 *
 * @implements Rule<Class_>
 */
final readonly class BeImmutableRule implements Rule
{
    /** @var list<string> */
    private array $excludedClasses;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{
     *     excludedClasses?: list<string>
     * } $options
     */
    public function __construct(array $options = [])
    {
        $this->excludedClasses = $options['excludedClasses'] ?? [];
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isAbstract() || $node->isAnonymous() || $node->namespacedName === null) {
            return [];
        }

        if ($node->isReadonly()) {
            return [];
        }

        $className = $node->namespacedName->toString();

        if (in_array($className, $this->excludedClasses, true)) {
            return [];
        }

        $errors = [];

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Property && !$stmt->isStatic() && !$stmt->isReadonly()) {
                $errors = [...$errors, ...$this->errorsForProperty($stmt, $node)];
            }
        }

        return $errors;
    }

    /**
     * Returns errors for each non-readonly property in the given property node.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function errorsForProperty(Property $property, Class_ $class): array
    {
        if ($class->name === null) {
            throw new ShouldNotHappenException();
        }

        $className = $class->name->toString();
        $errors = [];

        foreach ($property->props as $prop) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Property $%s in class %s must be readonly to ensure immutability.',
                    $prop->name->toString(),
                    $className,
                ),
            )
                ->identifier('haspadar.immutable')
                ->line($prop->getLine())
                ->build();
        }

        return $errors;
    }
}
