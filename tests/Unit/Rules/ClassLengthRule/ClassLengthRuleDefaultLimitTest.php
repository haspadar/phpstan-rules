<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ClassLengthRule;

use Haspadar\PHPStanRules\Rules\ClassLengthRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ClassLengthRule> */
final class ClassLengthRuleDefaultLimitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ClassLengthRule();
    }

    #[Test]
    public function reportsErrorWhenClassExceedsDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/LongDefaultClass.php'],
            [
                ['Class LongDefaultClass is 502 lines long. Maximum allowed is 500.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsExactlyAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ClassLengthRule/ExactDefaultClass.php'],
            [],
        );
    }
}
