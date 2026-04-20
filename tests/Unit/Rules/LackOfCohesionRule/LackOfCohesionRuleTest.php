<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\LackOfCohesionRule;

use Haspadar\PHPStanRules\Rules\LackOfCohesionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<LackOfCohesionRule> */
final class LackOfCohesionRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new LackOfCohesionRule(1, ['minMethods' => 4, 'minProperties' => 2]);
    }

    #[Test]
    public function passesWhenClassIsCohesive(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/CohesiveClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassSplitsIntoDisjointGroups(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/DisjointClass.php'],
            [
                ['Class DisjointClass has lack of cohesion 2 (methods split into 2 disjoint groups). Maximum allowed is 1.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodsAreConnectedOnlyViaMutualCalls(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/MethodChainCohesiveClass.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/SuppressedDisjointClass.php'],
            [],
        );
    }

    #[Test]
    public function skipsAbstractClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/LackOfCohesionRule/AbstractDisjointClass.php'],
            [],
        );
    }
}
