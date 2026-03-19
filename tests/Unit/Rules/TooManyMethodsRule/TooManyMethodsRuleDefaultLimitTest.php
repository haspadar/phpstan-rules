<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TooManyMethodsRule;

use Haspadar\PHPStanRules\Rules\TooManyMethodsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TooManyMethodsRule> */
final class TooManyMethodsRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TooManyMethodsRule();
    }

    #[Test]
    public function passesWhenClassIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/ExactDefaultClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/LongDefaultClass.php'],
            [
                ['Class LongDefaultClass has 21 methods. Maximum allowed is 20.', 7],
            ],
        );
    }
}
