<?php

declare(strict_types=1);

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
 * Forbids generic class name suffixes that indicate unclear responsibility.
 *
 * Reports classes whose names end with a forbidden suffix such as Manager,
 * Handler, Helper, Util, etc. Abstract classes, anonymous classes, and classes
 * whose suffix appears in the allowed list are exempt.
 *
 * @implements Rule<Class_>
 */
final readonly class ForbiddenClassSuffixRule implements Rule
{
    /** @var list<string> */
    private array $forbiddenSuffixes;

    /** @var list<string> */
    private array $allowedSuffixes;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{
     *     forbiddenSuffixes?: list<string>,
     *     allowedSuffixes?: list<string>
     * } $options
     */
    public function __construct(array $options = [])
    {
        $this->forbiddenSuffixes = $options['forbiddenSuffixes'] ?? [
            'Manager',
            'Handler',
            'Processor',
            'Coordinator',
            'Helper',
            'Util',
            'Utils',
            'Utility',
            'Data',
            'Info',
            'Information',
            'Wrapper',
        ];
        $this->allowedSuffixes = $options['allowedSuffixes'] ?? [];
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->isAbstract() || $node->isAnonymous() || $node->name === null) {
            return [];
        }

        $className = $node->name->toString();
        $matchedSuffix = $this->findForbiddenSuffix($className);

        if ($matchedSuffix === '') {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    "Class %s uses forbidden suffix '%s'. Rename to describe its responsibility.",
                    $className,
                    $matchedSuffix,
                ),
            )
                ->identifier('haspadar.forbiddenClassSuffix')
                ->build(),
        ];
    }

    /**
     * Returns the matched forbidden suffix or empty string if none matches.
     */
    private function findForbiddenSuffix(string $className): string
    {
        foreach ($this->forbiddenSuffixes as $suffix) {
            if (!str_ends_with($className, $suffix)) {
                continue;
            }

            if ($this->isAllowed($className)) {
                continue;
            }

            return $suffix;
        }

        return '';
    }

    /**
     * Checks whether the class name ends with an allowed suffix.
     */
    private function isAllowed(string $className): bool
    {
        foreach ($this->allowedSuffixes as $allowed) {
            if (str_ends_with($className, $allowed)) {
                return true;
            }
        }

        return false;
    }
}
