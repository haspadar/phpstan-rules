<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Forbids public constants in classes.
 *
 * Reports every class constant that is explicitly or implicitly public.
 * Interfaces, enums, traits, anonymous classes, and abstract classes
 * with no namespacedName are skipped. Abstract named classes ARE checked
 * because they still expose public API through their constants.
 *
 * @implements Rule<Class_>
 */
final readonly class NeverUsePublicConstantsRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @param Class_ $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isAnonymous() || $node->name === null) {
            return [];
        }

        $shortName = $node->name->toString();
        $errors = [];

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassConst && $stmt->isPublic()) {
                $errors = [...$errors, ...$this->errorsForConst($stmt, $shortName)];
            }
        }

        return $errors;
    }

    /**
     * Returns errors for each public constant in the given node.
     *
     * @return list<IdentifierRuleError>
     */
    private function errorsForConst(ClassConst $classConst, string $className): array
    {
        $errors = [];

        foreach ($classConst->consts as $const) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Constant %s in class %s must not be public. Use private or protected visibility.',
                    $const->name->toString(),
                    $className,
                ),
            )
                ->identifier('haspadar.noPublicConstants')
                ->line($const->getStartLine())
                ->build();
        }

        return $errors;
    }
}
