<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\Switch_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks every `switch` statement in the analysed code.
 *
 * Two invariants are enforced:
 * - every `switch` must contain at least one `default` case; a `switch` without
 *   `default` silently ignores unexpected values, which violates the principle of
 *   explicit control flow;
 * - the `default` case must appear last in the case list; placing `default` earlier
 *   misleads readers and differs from every major style guide (Checkstyle, PMD).
 *
 * `match` expressions are not covered: PHP guarantees exhaustiveness via
 * `UnhandledMatchError` at runtime and the compiler can enforce it for enums.
 *
 * @implements Rule<Switch_>
 */
final readonly class SwitchDefaultRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Switch_::class;
    }

    /**
     * Analyses the switch node and returns errors for missing or misplaced default.
     *
     * @param Switch_ $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $cases = $node->cases;

        if ($cases === []) {
            return [];
        }

        $defaultCase = $this->findDefaultCase(array_values($cases));

        if ($defaultCase === []) {
            return [
                RuleErrorBuilder::message('Switch statement must have a default case.')
                    ->identifier('haspadar.switchDefault')
                    ->line($node->getStartLine())
                    ->build(),
            ];
        }

        $lastCase = end($cases);

        if ($defaultCase[0] !== $lastCase) {
            return [
                RuleErrorBuilder::message('Default case must be the last case in a switch statement.')
                    ->identifier('haspadar.switchDefault')
                    ->line($defaultCase[0]->getStartLine())
                    ->build(),
            ];
        }

        return [];
    }

    /**
     * Returns an array containing the default case node, or an empty array if not found.
     *
     * @param list<Case_> $cases
     * @return list<Case_>
     */
    private function findDefaultCase(array $cases): array
    {
        foreach ($cases as $case) {
            if ($case->cond === null) {
                return [$case];
            }
        }

        return [];
    }
}
