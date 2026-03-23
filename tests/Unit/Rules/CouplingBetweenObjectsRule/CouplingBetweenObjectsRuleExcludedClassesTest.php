<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\CouplingBetweenObjectsRule;

use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<CouplingBetweenObjectsRule> */
final class CouplingBetweenObjectsRuleExcludedClassesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new CouplingBetweenObjectsRule(5, ['excludedClasses' => [
            'Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule\TypeI',
            'Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule\TypeJ',
            'Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule\TypeG',
            'Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule\TypeH',
            'Haspadar\PHPStanRules\Tests\Fixtures\Rules\CouplingBetweenObjectsRule\TypeF',
        ]]);
    }

    #[Test]
    public function passesWhenExcludedClassesAreNotCounted(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/CouplingBetweenObjectsRule/ClassWithExcludedDependencies.php'],
            [],
        );
    }

}
