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
final class InstabilityRuleTest extends RuleTestCase
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
    public function reportsClassWithInstabilityAboveLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InstabilityRule/HighInstability.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\HighInstability\Unstable has instability 0.86 (Ce=6, Ca=1) which exceeds the allowed 0.50.',
                    7,
                ],
            ],
            'Class with Ce=6 and Ca=1 must exceed an explicit maxInstability=0.5 limit',
        );
    }
}
