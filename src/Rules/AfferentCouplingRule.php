<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;

/**
 * Reports classes exceeding the afferent coupling (Ca) threshold.
 *
 * Afferent coupling counts how many other classes in the analysed codebase depend on a given class.
 * Classes with high Ca become change bottlenecks: every modification risks breaking many downstream consumers.
 * Dependency data is produced by {@see \Haspadar\PHPStanRules\Collectors\ClassDependencyCollector} and inverted here into an incoming-edge graph.
 *
 * This is the initial skeleton that wires the collector into the rule pipeline without emitting errors yet.
 * Threshold checks will be introduced in follow-up changes.
 *
 * @implements Rule<CollectedDataNode>
 */
final readonly class AfferentCouplingRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * Collects dependency tuples and returns no errors.
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        return [];
    }
}
