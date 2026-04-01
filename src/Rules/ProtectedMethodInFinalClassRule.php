<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects protected methods in final classes and reports an error for each one.
 * A final class cannot be extended, so protected visibility is meaningless —
 * the method is unreachable from a subclass and should be private instead.
 *
 * @implements Rule<Class_>
 */
final readonly class ProtectedMethodInFinalClassRule implements Rule
{
    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Class_ $node */
        if (!$node->isFinal()) {
            return [];
        }

        $errors = [];

        foreach ($node->getMethods() as $method) {
            if (!$method->isProtected()) {
                continue;
            }

            $className = $node->name !== null
                ? $node->name->toString()
                : 'anonymous';

            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Method %s::%s() is protected in a final class. Use private instead.',
                    $className,
                    $method->name->toString(),
                ),
            )
                ->identifier('haspadar.protectedInFinal')
                ->build();
        }

        return $errors;
    }
}
