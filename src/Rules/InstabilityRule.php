<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Collectors\ClassDependencyCollector;
use Haspadar\PHPStanRules\Rules\InstabilityRule\DependencyGraph;
use Override;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports classes exceeding the instability (I) threshold of Robert C. Martin's metric triad.
 *
 * Instability is defined as I = Ce / (Ca + Ce), where Ce is efferent coupling (outgoing dependencies)
 * and Ca is afferent coupling (incoming dependencies). Values range in [0, 1]: I=0 marks a stable
 * abstraction that nothing depends on being changed; I=1 marks a leaf consumer that depends on many
 * others while no-one depends on it. Very high I on a class with substantial coupling usually means
 * either dead code (no incoming references despite many outgoing) or a service that does too much.
 *
 * The same dependency graph produced by {@see \Haspadar\PHPStanRules\Collectors\ClassDependencyCollector}
 * for afferent coupling is reused here. Classes with fewer than `$minDependencies` total edges are
 * skipped because I becomes noisy on small graphs. Interfaces and abstract classes can be ignored via
 * option flags; arbitrary FQCNs can be excluded through `excludedClasses` (case-insensitive).
 *
 * @implements Rule<CollectedDataNode>
 */
final readonly class InstabilityRule implements Rule
{
    private bool $ignoreInterfaces;

    private bool $ignoreAbstract;

    /** @var list<string> Lowercased FQCNs that must never be reported. */
    private array $excludedClasses;

    /**
     * Stores the instability upper bound, minimum dependency threshold, skip flags, and exclusion list.
     *
     * @param float $maxInstability Inclusive upper bound on I = Ce / (Ca + Ce); classes above the threshold are reported.
     * @param int $minDependencies Minimum total coupling (Ca + Ce) required before the class is evaluated.
     * @param array{
     *     ignoreInterfaces?: bool,
     *     ignoreAbstract?: bool,
     *     excludedClasses?: list<string>
     * } $options Filters that skip interfaces, abstract classes, and explicit FQCNs.
     */
    public function __construct(
        private float $maxInstability = 0.8,
        private int $minDependencies = 5,
        array $options = [],
    ) {
        $this->ignoreInterfaces = $options['ignoreInterfaces'] ?? true;
        $this->ignoreAbstract = $options['ignoreAbstract'] ?? true;
        $this->excludedClasses = array_map(
            static fn(string $class): string => strtolower(ltrim($class, '\\')),
            $options['excludedClasses'] ?? [],
        );
    }

    #[Override]
    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * Computes instability per class from the dependency graph and reports classes above the threshold.
     *
     * @param CollectedDataNode $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $collected = $node->get(ClassDependencyCollector::class);
        [$declarations, $incoming, $efferent] = (new DependencyGraph())->build($collected);
        $errors = [];

        foreach ($declarations as $lowerFqcn => $meta) {
            $error = $this->evaluate($meta, count($incoming[$lowerFqcn] ?? []), $efferent[$lowerFqcn] ?? 0);

            if ($error !== null) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * Returns a rule error for the declaration when it breaches the configured instability threshold.
     *
     * @param array{class: string, kind: string, abstract: bool, file: string, line: int} $meta
     * @throws ShouldNotHappenException
     */
    private function evaluate(array $meta, int $afferent, int $efferent): ?IdentifierRuleError
    {
        if ($this->shouldSkip($meta)) {
            return null;
        }

        $total = $afferent + $efferent;

        if ($total === 0 || $total < $this->minDependencies) {
            return null;
        }

        $instability = $efferent / $total;

        if ($instability <= $this->maxInstability) {
            return null;
        }

        return $this->buildError([
            'meta' => $meta,
            'instability' => $instability,
            'afferent' => $afferent,
            'efferent' => $efferent,
        ]);
    }

    /**
     * Returns true when the declaration must be skipped due to configured ignore flags or exclusion list.
     *
     * @param array{class: string, kind: string, abstract: bool, file: string, line: int} $meta
     */
    private function shouldSkip(array $meta): bool
    {
        if ($this->ignoreInterfaces && $meta['kind'] === 'interface') {
            return true;
        }

        if ($this->ignoreAbstract && $meta['abstract']) {
            return true;
        }

        return in_array(strtolower(ltrim($meta['class'], '\\')), $this->excludedClasses, true);
    }

    /**
     * Builds the rule error payload for a single class from the pre-computed metrics bundle.
     *
     * @param array{
     *     meta: array{class: string, kind: string, abstract: bool, file: string, line: int},
     *     instability: float,
     *     afferent: int,
     *     efferent: int
     * } $payload
     * @throws ShouldNotHappenException
     */
    private function buildError(array $payload): IdentifierRuleError
    {
        $meta = $payload['meta'];

        return RuleErrorBuilder::message(
            sprintf(
                'Class %s has instability %.2f (Ce=%d, Ca=%d) which exceeds the allowed %.2f.',
                $meta['class'],
                $payload['instability'],
                $payload['efferent'],
                $payload['afferent'],
                $this->maxInstability,
            ),
        )
            ->identifier('haspadar.instability')
            ->file($meta['file'])
            ->line($meta['line'])
            ->build();
    }
}
