<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\IllegalThrowsRule;

use Haspadar\PHPStanRules\Rules\IllegalThrowsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<IllegalThrowsRule> */
final class IllegalThrowsRuleIgnoreOverriddenTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new IllegalThrowsRule(options: ['ignoreOverriddenMethods' => false]);
    }

    #[Test]
    public function reportsOverriddenMethodWhenIgnoreDisabled(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/IllegalThrowsRule/ClassWithOverriddenMethodThrows.php'],
            [
                ['Throwing RuntimeException is not allowed.', 10],
            ],
        );
    }
}
