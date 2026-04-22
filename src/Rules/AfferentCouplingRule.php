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
 * The `ignoreInterfaces` and `ignoreAbstract` option flags skip reporting for interfaces and abstract classes,
 * which are expected to be widely implemented or extended by design. The `excludedClasses` option lists
 * FQCNs that must never be reported, regardless of their incoming edge count; matching is case-insensitive.
 *
 * @implements Rule<CollectedDataNode>
 */
final readonly class AfferentCouplingRule implements Rule
{
    private bool $ignoreInterfaces;

    private bool $ignoreAbstract;

    /** @var list<string> Lowercased FQCNs that must never be reported. */
    private array $excludedClasses;

    /**
     * Stores the inclusive upper bound on afferent coupling per class, skip flags, and the exclusion list.
     *
     * @param int $maxAfferent Maximum number of incoming class references allowed per class.
     * @param array{
     *     ignoreInterfaces?: bool,
     *     ignoreAbstract?: bool,
     *     excludedClasses?: list<string>
     * } $options
     */
    public function __construct(private int $maxAfferent = 14, array $options = [])
    {
        $this->ignoreInterfaces = $options['ignoreInterfaces'] ?? false;
        $this->ignoreAbstract = $options['ignoreAbstract'] ?? false;
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
            if ($this->shouldSkip($meta)) {
                continue;
            }

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
     * @return array{array<string, array{class: string, kind: string, abstract: bool, file: string, line: int}>, array<string, array<string, true>>}
     */
    private function buildGraph(array $collected): array
    {
        $declarations = [];
        $incoming = [];

        foreach ($collected as $file => $entries) {
            foreach ($entries as $entry) {
                $lowerFqcn = strtolower($entry['class']);
                $declarations[$lowerFqcn] = [
                    'class' => $entry['class'],
                    'kind' => $entry['kind'],
                    'abstract' => $entry['abstract'],
                    'file' => $file,
                    'line' => $entry['line'],
                ];

                foreach ($entry['dependencies'] as $dependency) {
                    $incoming[$dependency][$lowerFqcn] = true;
                }
            }
        }

        return [$declarations, $incoming];
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
     * Builds the rule error payload for a single target class.
     *
     * @param array{class: string, kind: string, abstract: bool, file: string, line: int} $meta
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
