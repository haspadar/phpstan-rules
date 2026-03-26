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
 * Detects public static methods in classes and reports an error for each one.
 * Static methods are not polymorphic, cannot work with object state, and create
 * hard dependencies that make code difficult to test. Only public visibility is
 * checked — private static helper methods are permitted.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class ProhibitPublicStaticMethodsRule implements Rule
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
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var ClassMethod $node */
        if (!$node->isPublic() || !$node->isStatic()) {
            return [];
        }

        $reflection = $scope->getClassReflection();
        $className = $reflection !== null ? $reflection->getName() : 'anonymous'; // @codeCoverageIgnore

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() is public static. Static methods are prohibited.',
                    $className,
                    $node->name->toString(),
                ),
            )
                ->identifier('haspadar.noPublicStatic')
                ->build(),
        ];
    }
}
