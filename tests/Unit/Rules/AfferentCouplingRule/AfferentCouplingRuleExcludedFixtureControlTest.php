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
 * Control test for the excluded-target fixture: proves it triggers the rule when excludedClasses is empty.
 *
 * @extends RuleTestCase<AfferentCouplingRule>
 */
final class AfferentCouplingRuleExcludedFixtureControlTest extends RuleTestCase
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
    public function reportsExcludedFixtureWhenExcludedClassesEmpty(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/ExcludedTarget.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\ExcludedTarget\ExcludedHotTarget has afferent coupling 3 which exceeds the allowed 2.',
                    7,
                ],
            ],
            'Excluded-target fixture with three consumers must be reported when excludedClasses is empty',
        );
    }
}
