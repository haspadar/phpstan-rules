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

/**
 * Control test for the interface fixture: proves it triggers the rule when ignoreInterfaces is disabled.
 *
 * @extends RuleTestCase<AfferentCouplingRule>
 */
final class AfferentCouplingRuleInterfaceFixtureControlTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new AfferentCouplingRule(maxAfferent: 2);
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
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/InterfaceWithManyAfferent.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\InterfaceWithManyAfferent\HotInterface has afferent coupling 3 which exceeds the allowed 2.',
                    7,
                ],
            ],
            'Interface fixture with three implementors must be reported when ignoreInterfaces is disabled',
        );
    }
}
