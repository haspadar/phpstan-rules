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
final class AfferentCouplingRuleDefaultLimitTest extends RuleTestCase
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
    public function passesWhenAfferentCouplingFitsWithinDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/FewAfferent.php'],
            [],
            'Codebase with only a handful of consumers must not trigger the default afferent limit',
        );
    }

    #[Test]
    public function passesWhenAfferentCouplingIsExactlyAtDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/ExactAfferent.php'],
            [],
            'Ca == 14 must not trigger the default limit',
        );
    }

    #[Test]
    public function reportsClassesExceedingDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/TooManyAfferent.php'],
            [
                [
                    'Class Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\TooManyAfferent\HotTarget has afferent coupling 15 which exceeds the allowed 14.',
                    7,
                ],
            ],
            'Ca == 15 must trigger the default limit of 14',
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/AfferentCouplingRule/SuppressedTooMany.php'],
            [],
            '@phpstan-ignore haspadar.afferentCoupling must suppress the error',
        );
    }
}
