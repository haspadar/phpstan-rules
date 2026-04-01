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
 * Reports every non-final concrete class. A class must be either abstract (designed
 * for inheritance) or final (inheritance forbidden). Excluded: abstract classes,
 * anonymous classes. Interfaces and traits are never Class_ nodes and are never visited.
 *
 * @implements Rule<Class_>
 */
final readonly class FinalClassRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Class_ $node */

        if ($node->isAbstract() || $node->isAnonymous() || $node->isFinal() || $node->name === null) {
            return [];
        }

        $name = $node->name->toString();

        return [
            RuleErrorBuilder::message(
                sprintf('Class %s must be declared as final.', $name),
            )
                ->identifier('haspadar.finalClass')
                ->build(),
        ];
    }
}
