<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports an interface that declares more methods than the configured maximum.
 *
 * Counts all methods declared directly in the interface body.
 * Methods inherited via `extends` are not counted — only own declarations.
 * Enforces the Interface Segregation Principle: one interface = one narrow contract.
 *
 * @implements Rule<Interface_>
 */
final readonly class KeepInterfacesShortRule implements Rule
{
    /**
     * Constructs the rule with the given method limit.
     *
     * @param int $maxMethods Maximum number of methods per interface.
     */
    public function __construct(private int $maxMethods = 10) {}

    #[Override]
    public function getNodeType(): string
    {
        return Interface_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Interface_ $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node->name !== null);

        $count = count($node->getMethods());

        if ($count <= $this->maxMethods) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Interface %s has %d methods. Maximum allowed is %d.',
                    $node->name->toString(),
                    $count,
                    $this->maxMethods,
                ),
            )
                ->identifier('haspadar.interfaceMethods')
                ->build(),
        ];
    }
}
