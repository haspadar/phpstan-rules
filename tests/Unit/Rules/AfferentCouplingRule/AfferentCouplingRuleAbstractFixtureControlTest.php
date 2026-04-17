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
 * Control test for the abstract-class fixture: proves it triggers the rule when ignoreAbstract is disabled.
 *
 * @extends RuleTestCase<AfferentCouplingRule>
 */
final class AfferentCouplingRuleAbstractFixtureControlTest extends RuleTestCase
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
    public function reportsAbstractFixtureWhenIgnoreAbstractDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/AbstractWithManyAfferent.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\AbstractWithManyAfferent\HotAbstract has afferent coupling 3 which exceeds the allowed 2.',
                    7,
                ],
            ],
            'Abstract-class fixture with three consumers must be reported when ignoreAbstract is disabled',
        );
    }
}
