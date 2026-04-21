<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\InstabilityRule;

/**
 * Builds afferent/efferent views over the class-dependency payload produced by the collector.
 *
 * For every collected declaration it records the class metadata, the set of lowercased FQCNs
 * that reference it (incoming edges for afferent coupling), and the size of its outgoing edge
 * set (efferent coupling). The resulting maps are keyed by lowercased FQCN so lookups stay
 * consistent with the normalisation applied by the collector.
 */
final readonly class DependencyGraph
{
    /**
     * Folds the collector payload into declarations, incoming edges and efferent counts.
     *
     * @param array<string, list<array{class: string, kind: string, abstract: bool, line: int, dependencies: list<string>}>> $collected
     * @return array{
     *     array<string, array{class: string, kind: string, abstract: bool, file: string, line: int}>,
     *     array<string, array<string, true>>,
     *     array<string, int>
     * }
     */
    public function build(array $collected): array
    {
        $declarations = [];
        $incoming = [];
        $efferent = [];

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
                $efferent[$lowerFqcn] = count($entry['dependencies']);

                foreach ($entry['dependencies'] as $dependency) {
                    $incoming[$dependency][$lowerFqcn] = true;
                }
            }
        }

        return [$declarations, $incoming, $efferent];
    }
}
