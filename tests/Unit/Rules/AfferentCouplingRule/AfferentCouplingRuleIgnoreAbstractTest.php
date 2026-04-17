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
final class AfferentCouplingRuleIgnoreAbstractTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new AfferentCouplingRule(maxAfferent: 2, options: ['ignoreAbstract' => true]);
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
    public function skipsAbstractClassesWhenIgnoreAbstractEnabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/AbstractWithManyAfferent.php'],
            [],
            'Abstract class with Ca above the limit must be skipped when ignoreAbstract=true',
        );
    }

    #[Test]
    public function stillReportsConcreteClassesWhenIgnoreAbstractEnabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/SmallLimitTooMany.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\SmallLimitTooMany\HotTarget has afferent coupling 3 which exceeds the allowed 2.',
                    7,
                ],
            ],
            'Concrete classes must still be reported when ignoreAbstract=true',
        );
    }
}
