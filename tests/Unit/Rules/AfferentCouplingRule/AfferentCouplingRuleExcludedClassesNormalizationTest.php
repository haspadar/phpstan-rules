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
 * Verifies that excludedClasses entries are normalized: leading backslash stripped, case folded.
 *
 * @extends RuleTestCase<AfferentCouplingRule>
 */
final class AfferentCouplingRuleExcludedClassesNormalizationTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new AfferentCouplingRule(
            maxAfferent: 2,
            options: [
                'excludedClasses' => [
                    '\\HASPADAR\\phpstanrules\\Tests\\Fixtures\\Rules\\AfferentCouplingRule\\ExcludedTarget\\excludedHOTTarget',
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
    public function skipsClassWhenExcludedEntryHasLeadingBackslashAndMixedCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/ExcludedTarget.php'],
            [],
            'excludedClasses must match regardless of leading backslash or case differences',
        );
    }
}
