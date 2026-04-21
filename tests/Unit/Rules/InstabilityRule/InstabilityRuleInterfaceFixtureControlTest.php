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
 * Control test for the interface fixture: proves it triggers the rule when ignoreInterfaces is disabled.
 *
 * @extends RuleTestCase<InstabilityRule>
 */
final class InstabilityRuleInterfaceFixtureControlTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InstabilityRule(
            maxInstability: 0.5,
            minDependencies: 5,
            options: ['ignoreInterfaces' => false],
        );
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
    public function reportsInterfaceFixtureWhenIgnoreInterfacesDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InstabilityRule/InterfaceUnstable.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\InterfaceUnstable\UnstableInterface has instability 1.00 (Ce=6, Ca=0) which exceeds the allowed 0.50.',
                    7,
                ],
            ],
            'Interface fixture with six outgoing dependencies must be reported when ignoreInterfaces is disabled',
        );
    }
}
