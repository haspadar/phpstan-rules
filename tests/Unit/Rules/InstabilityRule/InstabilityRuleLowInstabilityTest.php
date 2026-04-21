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

/** @extends RuleTestCase<InstabilityRule> */
final class InstabilityRuleLowInstabilityTest extends RuleTestCase
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
    public function skipsStableClassWithLowInstability(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InstabilityRule/LowInstability.php'],
            [],
            'Stable class with Ce=0 and Ca=5 must not be reported (I=0)',
        );
    }
}
