<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports class constants that lack a native type declaration.
 * PHP 8.3 introduced typed class constants (`const string FOO = 'bar'`).
 * This rule ensures every constant in a class, interface, or enum
 * has an explicit type, improving type safety and self-documentation.
 *
 * @implements Rule<ClassConst>
 */
final readonly class ClassConstantTypeHintRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return ClassConst::class;
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
        /** @var ClassConst $node */
        if ($node->type !== null) {
            return [];
        }

        $reflection = $scope->getClassReflection();

        if ($reflection === null) {
            throw new ShouldNotHappenException();
        }

        $className = $reflection->getName();
        $errors = [];

        foreach ($node->consts as $const) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Constant %s::%s must have a native type declaration.',
                    $className,
                    $const->name->toString(),
                ),
            )
                ->identifier('haspadar.classConstantType')
                ->build();
        }

        return $errors;
    }
}
