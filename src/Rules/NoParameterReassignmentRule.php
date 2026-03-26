<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects reassignment of method or constructor parameters.
 * A parameter must not be assigned a new value within the method body.
 * Use a local variable instead to preserve the original parameter value
 * and make the transformation explicit.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class NoParameterReassignmentRule implements Rule
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
        $paramNames = $this->parameterNames($node);

        if ($paramNames === []) {
            return [];
        }

        $errors = [];

        foreach ($node->stmts ?? [] as $stmt) {
            foreach ($stmt->getSubNodeNames() as $subName) {
                $sub = $stmt->$subName;

                if (!$sub instanceof Assign) {
                    continue;
                }

                if (!$sub->var instanceof Variable) {
                    continue;
                }

                $varName = $sub->var->name;

                if (!is_string($varName) || !in_array($varName, $paramNames, true)) {
                    continue;
                }

                $reflection = $scope->getClassReflection();
                $className = $reflection !== null ? $reflection->getName() : 'anonymous';

                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Parameter $%s must not be reassigned in method %s() of %s.',
                        $varName,
                        $node->name->toString(),
                        $className,
                    ),
                )
                    ->identifier('haspadar.noParameterReassignment')
                    ->line($sub->getLine())
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * @param ClassMethod $node
     *
     * @return list<string>
     */
    private function parameterNames(ClassMethod $node): array
    {
        $names = [];

        foreach ($node->params as $param) {
            if ($param->var instanceof Variable && is_string($param->var->name)) {
                $names[] = $param->var->name;
            }
        }

        return $names;
    }
}
