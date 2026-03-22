<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CouplingBetweenObjectsRule;

use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CouplingBetweenObjectsRule> */
final class CouplingBetweenObjectsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CouplingBetweenObjectsRule(5);
    }

    #[Test]
    public function passesWhenClassFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/FewDependencies.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ManyDependencies.php'],
            [
                ['Class ManyDependencies has a coupling between objects value of 10. Maximum allowed is 5.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ExactDependencies.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/SuppressedManyDependencies.php'],
            [],
        );
    }
}
