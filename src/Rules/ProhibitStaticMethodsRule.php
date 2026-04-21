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
use PHPStan\ShouldNotHappenException;

/**
 * Detects static method declarations and reports an error for each one regardless of visibility.
 * Static methods are not polymorphic, cannot work with object state, and create hard dependencies
 * that make code difficult to test. Private and protected static helpers are treated the same as
 * public ones because they still encode procedural logic that bypasses the object lifecycle.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ProhibitStaticMethodsRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Reports an error when the analysed method declaration is static.
     *
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if (!$node->isStatic()) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection === null || $reflection->isAnonymous()
            ? 'class@anonymous'
            : $reflection->getName();

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() is static. Static methods are prohibited.',
                    $className,
                    $node->name->toString(),
                ),
            )
                ->identifier('haspadar.staticMethod')
                ->build(),
        ];
    }
}
