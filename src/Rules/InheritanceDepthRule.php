<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports classes exceeding the configured inheritance depth (DIT).
 *
 * Depth is the number of `extends` ancestors from the analysed class up to the root: a class with no parent
 * has depth 0, `class B extends A` gives B depth 1, and so on. Interfaces (`implements`) and traits (`use`)
 * never contribute to the count. Anonymous classes are skipped because they cannot be named in configuration
 * and rarely participate in multi-level hierarchies.
 *
 * The `excludedClasses` option lists FQCNs that must never be reported regardless of their depth; matching
 * normalizes a leading backslash and is case-insensitive.
 *
 * @implements Rule<Class_>
 */
final readonly class InheritanceDepthRule implements Rule
{
    /** @var list<string> Normalized (lowercased, leading-backslash-stripped) FQCNs that must never be reported. */
    private array $excludedClasses;

    /**
     * Stores the inclusive upper bound on inheritance depth and the exclusion list.
     *
     * @param array{
     *     excludedClasses?: list<string>
     * } $options
     */
    public function __construct(
        private ReflectionProvider $reflectionProvider,
        private int $maxDepth = 3,
        array $options = [],
    ) {
        $this->excludedClasses = array_map(
            static fn(string $class): string => strtolower(ltrim($class, '\\')),
            $options['excludedClasses'] ?? [],
        );
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
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name === null || $node->namespacedName === null) {
            return [];
        }

        $fqcn = $node->namespacedName->toString();

        if (in_array(strtolower($fqcn), $this->excludedClasses, true)) {
            return [];
        }

        if (!$this->reflectionProvider->hasClass($fqcn)) {
            return [];
        }

        $depth = count($this->reflectionProvider->getClass($fqcn)->getParents());

        if ($depth <= $this->maxDepth) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Class %s has inheritance depth %d which exceeds the allowed %d.',
                    $node->name->toString(),
                    $depth,
                    $this->maxDepth,
                ),
            )
                ->identifier('haspadar.inheritanceDepth')
                ->build(),
        ];
    }
}
