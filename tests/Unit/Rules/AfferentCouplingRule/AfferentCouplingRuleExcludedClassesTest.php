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
final class AfferentCouplingRuleExcludedClassesTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new AfferentCouplingRule(
            maxAfferent: 2,
            options: [
                'excludedClasses' => [
                    'Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\AfferentCouplingRule\\ExcludedTarget\\ExcludedHotTarget',
                ],
            ],
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
    public function skipsClassListedInExcludedClasses(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/ExcludedTarget.php'],
            [],
            'Class in excludedClasses must never be reported even when Ca exceeds the limit',
        );
    }

    #[Test]
    public function stillReportsOtherClassesWhenExcludedClassesConfigured(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/SmallLimitTooMany.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\SmallLimitTooMany\HotTarget has afferent coupling 3 which exceeds the allowed 2.',
                    7,
                ],
            ],
            'Classes not listed in excludedClasses must still be reported',
        );
    }
}
