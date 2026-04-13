<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\BeImmutableRule;

use Haspadar\PHPStanRules\Rules\BeImmutableRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<BeImmutableRule> */
final class BeImmutableRuleDefaultTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new BeImmutableRule();
    }

    #[Test]
    public function reportsErrorWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ClassWithMutableProperty.php'],
            [
                ['Property $name in class ClassWithMutableProperty must be readonly to ensure immutability.', 9],
            ],
        );
    }

    #[Test]
    public function passesReadonlyPropertyWithDefaultOptions(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/BeImmutableRule/ClassWithReadonlyProperty.php'],
            [],
        );
    }
}
