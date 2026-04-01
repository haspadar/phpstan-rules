<?php

declare(strict_types = 1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Catch_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects catch blocks that catch overly broad exception types.
 * Catching Exception, Throwable, RuntimeException or Error hides real errors
 * and makes it impossible to handle specific failure modes correctly.
 * The list of illegal class names is configurable.
 *
 * @implements Rule<Catch_>
 */
final readonly class IllegalCatchRule implements Rule
{
    /** @param list<string> $illegalClassNames Short class names (without leading backslash) that are forbidden in catch */
    public function __construct(
        private array $illegalClassNames = ['Exception', 'Throwable', 'RuntimeException', 'Error'],
    ) {}

    #[Override]
    public function getNodeType(): string
    {
        return Catch_::class;
    }

    /**
     * @psalm-param Catch_ $node
     * @throws \PHPStan\ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        foreach ($node->types as $type) {
            $shortName = $type->getLast();

            if (!in_array($shortName, $this->illegalClassNames, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Catching %s is not allowed.', $shortName),
            )
                ->identifier('haspadar.illegalCatch')
                ->line($type->getStartLine())
                ->build();
        }

        return $errors;
    }
}
