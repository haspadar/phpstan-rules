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
use PHPStan\ShouldNotHappenException;

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
     * Stores the reflection provider, the inclusive upper bound on inheritance depth, and the exclusion list.
     *
     * @param ReflectionProvider $reflectionProvider Resolves each analysed class to its inheritance chain
     * @param int $maxDepth Inclusive upper bound on DIT; classes with depth greater than this are reported
     * @param array{
     *     excludedClasses?: list<string>
     * } $options FQCNs listed under `excludedClasses` are never reported
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

    /**
     * Returns the PHP-Parser node type this rule reacts to.
     */
    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Computes the inheritance depth of the class declared by `$node` and reports it when the depth exceeds the configured limit.
     *
     * @param Class_ $node
     * @throws ShouldNotHappenException
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

        assert($this->reflectionProvider->hasClass($fqcn));

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
