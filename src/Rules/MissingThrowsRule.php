<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\MethodReturnStatementsNode;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Rules\Exceptions\MissingCheckedExceptionInThrowsCheck;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports methods that throw a checked exception missing from the PHPDoc @throws tag.
 *
 * Replaces the built-in exceptions.check.missingCheckedExceptionInThrows so that
 * overridden methods (those whose name exists on a parent class or implemented
 * interface) inherit @throws from the parent contract and do not have to repeat
 * it. Delegates the actual detection to PHPStan's MissingCheckedExceptionInThrowsCheck
 * helper so the sophisticated throw-point collection stays in PHPStan core.
 *
 * @implements Rule<MethodReturnStatementsNode>
 */
final readonly class MissingThrowsRule implements Rule
{
    private bool $skipOverridden;

    /**
     * Constructs the rule with the shared PHPStan throw-check helper and configuration options.
     *
     * @param MissingCheckedExceptionInThrowsCheck $check Shared throw-point check from PHPStan core
     * @param array{skipOverridden?: bool} $options When skipOverridden is true, methods that override a parent or implement an interface method are skipped
     */
    public function __construct(
        private MissingCheckedExceptionInThrowsCheck $check,
        array $options = [],
    ) {
        $this->skipOverridden = $options['skipOverridden'] ?? true;
    }

    #[Override]
    public function getNodeType(): string
    {
        return MethodReturnStatementsNode::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param MethodReturnStatementsNode $node
     * @throws MissingMethodFromReflectionException
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $method = $node->getMethodReflection();

        if ($this->skipOverridden && $this->isOverridden($method)) {
            return [];
        }

        $errors = [];
        $throwPoints = $node->getStatementResult()->getThrowPoints();

        /** @phpstan-ignore phpstanApi.method (reuse PHPStan internal helper for checked-exception detection) */
        $missing = $this->check->check(
            $method->getThrowType(),
            $throwPoints,
        );

        foreach ($missing as [$className, $throwPointNode]) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    "Method %s::%s() throws checked exception %s but it's missing from the PHPDoc @throws tag.",
                    $method->getDeclaringClass()->getDisplayName(),
                    $method->getName(),
                    $className,
                ),
            )
                ->line($throwPointNode->getStartLine())
                ->identifier('haspadar.missingThrows')
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the method exists on any parent class or implemented interface.
     *
     * Private parent methods do not count as inherited because PHP treats them
     * as independent declarations. Only non-private ancestor methods establish
     * an override relationship from which @throws can be inherited.
     *
     * @throws MissingMethodFromReflectionException
     */
    private function isOverridden(MethodReflection $method): bool
    {
        $declaringClass = $method->getDeclaringClass();
        $name = $method->getName();
        $parent = $declaringClass->getParentClass();

        if ($parent !== null && $parent->hasMethod($name) && !$parent->getNativeMethod($name)->isPrivate()) {
            return true;
        }

        foreach ($declaringClass->getInterfaces() as $interface) {
            if ($interface->hasMethod($name)) {
                return true;
            }
        }

        return false;
    }
}
