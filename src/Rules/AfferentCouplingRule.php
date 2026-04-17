<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Collectors\ClassDependencyCollector;
use Override;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Reports classes exceeding the afferent coupling (Ca) threshold.
 *
 * Afferent coupling counts how many other classes in the analysed codebase depend on a given class.
 * Classes with high Ca become change bottlenecks: every modification risks breaking many downstream consumers.
 * Dependency data is produced by {@see \Haspadar\PHPStanRules\Collectors\ClassDependencyCollector} and inverted
 * here into an incoming-edge graph: for every collected declaration we count how many distinct consumers list
 * it among their outbound dependencies, and emit an error when the count exceeds `$maxAfferent`.
 *
 * @implements Rule<CollectedDataNode>
 */
final readonly class AfferentCouplingRule implements Rule
{
    /** Stores the inclusive upper bound on afferent coupling per class. */
    public function __construct(private int $maxAfferent = 14) {}

    #[Override]
    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * Inverts the dependency graph and reports classes whose incoming edge count exceeds the threshold.
     *
     * @param CollectedDataNode $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        [$declarations, $incoming] = $this->buildGraph($this->readCollected($node));
        $errors = [];

        foreach ($declarations as $lowerFqcn => $meta) {
            $afferent = count($incoming[$lowerFqcn] ?? []);

            if ($afferent <= $this->maxAfferent) {
                continue;
            }

            $errors[] = $this->buildError($meta, $afferent);
        }

        return $errors;
    }

    /**
     * Reads the dependency-collector payload from the CollectedDataNode wired for this rule.
     *
     * @return array<string, list<array{class: string, kind: string, abstract: bool, line: int, dependencies: list<string>}>>
     */
    private function readCollected(CollectedDataNode $node): array
    {
        return $node->get(ClassDependencyCollector::class);
    }

    /**
     * Returns a pair of maps: declarations keyed by lowercased FQCN and incoming edges keyed by target FQCN.
     *
     * @param array<string, list<array{class: string, kind: string, abstract: bool, line: int, dependencies: list<string>}>> $collected
     * @return array{array<string, array{class: string, file: string, line: int}>, array<string, array<string, true>>}
     */
    private function buildGraph(array $collected): array
    {
        $declarations = [];
        $incoming = [];

        foreach ($collected as $file => $entries) {
            foreach ($entries as $entry) {
                $lowerFqcn = strtolower($entry['class']);
                $declarations[$lowerFqcn] = ['class' => $entry['class'], 'file' => $file, 'line' => $entry['line']];

                foreach ($entry['dependencies'] as $dependency) {
                    $incoming[$dependency][$lowerFqcn] = true;
                }
            }
        }

        return [$declarations, $incoming];
    }

    /**
     * Builds the rule error payload for a single target class.
     *
     * @param array{class: string, file: string, line: int} $meta
     * @throws ShouldNotHappenException
     */
    private function buildError(array $meta, int $afferent): IdentifierRuleError
    {
        return RuleErrorBuilder::message(
            sprintf(
                'Class %s has afferent coupling %d which exceeds the allowed %d.',
                $meta['class'],
                $afferent,
                $this->maxAfferent,
            ),
        )
            ->identifier('haspadar.afferentCoupling')
            ->file($meta['file'])
            ->line($meta['line'])
            ->build();
    }
}
