<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\AfferentCouplingRule;

use Haspadar\PHPStanRules\Collectors\ClassDependencyCollector;
use Haspadar\PHPStanRules\Rules\AfferentCouplingRule;
use Override;
use PHPStan\Collectors\Collector;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<AfferentCouplingRule> */
final class AfferentCouplingRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new AfferentCouplingRule();
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
    public function collectorAndRuleWireUpWithoutErrors(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/FewAfferent.php'],
            [],
            'Stub rule must not emit errors; pipeline wiring is the only thing being verified',
        );
    }
}
