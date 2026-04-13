<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\BeImmutableRule;

use Haspadar\PHPStanRules\Rules\BeImmutableRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<BeImmutableRule> */
final class BeImmutableRuleExcludedClassTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new BeImmutableRule([
            'excludedClasses' => [
                'Haspadar\PHPStanRules\Tests\Fixtures\Rules\BeImmutableRule\ClassWithExcludedFqcn',
            ],
        ]);
    }

    #[Test]
    public function passesWhenClassIsExcluded(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ClassWithExcludedFqcn.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassIsNotExcluded(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ClassWithMutableProperty.php'],
            [
                ['Property $name in class ClassWithMutableProperty must be readonly to ensure immutability.', 9],
            ],
        );
    }
}
