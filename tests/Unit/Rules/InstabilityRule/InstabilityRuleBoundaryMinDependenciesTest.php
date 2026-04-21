<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InstabilityRule;

use Haspadar\PHPStanRules\Collectors\ClassDependencyCollector;
use Haspadar\PHPStanRules\Rules\InstabilityRule;
use Override;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Boundary test: classes with Ca+Ce exactly equal to minDependencies must still be checked.
 *
 * @extends RuleTestCase<InstabilityRule>
 */
final class InstabilityRuleBoundaryMinDependenciesTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InstabilityRule(maxInstability: 0.5, minDependencies: 5);
    }

    /**
     * Registers the dependency collector so PHPStan feeds the rule with cross-file dependency data.
     *
     * @return list<Collector<\PhpParser\Node, mixed>>
     */
    #[Override]
    protected function getCollectors(): array
    {
        return [new ClassDependencyCollector()];
    }

    #[Test]
    public function reportsClassWhenTotalDependenciesEqualsMinDependencies(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InstabilityRule/BoundaryMinDependencies.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\BoundaryMinDependencies\Boundary has instability 0.80 (Ce=4, Ca=1) which exceeds the allowed 0.50.',
                    7,
                ],
            ],
            'Boundary class with Ca+Ce == minDependencies must be evaluated (use < not <=)',
        );
    }
}
