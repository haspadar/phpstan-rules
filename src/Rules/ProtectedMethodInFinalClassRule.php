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
use PHPStan\ShouldNotHappenException;

/**
 * Detects protected methods in final classes and reports an error for each one.
 * A final class cannot be extended, so protected visibility is meaningless —
 * the method is unreachable from a subclass and should be private instead.
 *
 * @implements Rule<Class_>
 */
final readonly class ProtectedMethodInFinalClassRule implements Rule
{
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
