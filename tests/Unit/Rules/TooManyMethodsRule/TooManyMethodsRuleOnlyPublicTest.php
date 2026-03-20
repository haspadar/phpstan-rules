<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TooManyMethodsRule;

use Haspadar\PHPStanRules\Rules\TooManyMethodsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TooManyMethodsRule> */
final class TooManyMethodsRuleOnlyPublicTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TooManyMethodsRule(5, ['onlyPublic' => true]);
    }

    #[Test]
    public function passesWhenNonPublicMethodsAreExcludedFromCount(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/ClassWithNonPublicMethods.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenPublicMethodsExceedLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/LongPublicClass.php'],
            [
                ['Class LongPublicClass has 6 methods. Maximum allowed is 5.', 7],
            ],
        );
    }
}
