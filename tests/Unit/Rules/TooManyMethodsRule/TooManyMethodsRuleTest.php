<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\TooManyMethodsRule;

use Haspadar\PHPStanRules\Rules\TooManyMethodsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<TooManyMethodsRule> */
final class TooManyMethodsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new TooManyMethodsRule(5);
    }

    #[Test]
    public function passesWhenClassFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/ShortClass.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenClassExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/LongClass.php'],
            [
                ['Class LongClass has 7 methods. Maximum allowed is 5.', 7],
            ],
        );
    }

    #[Test]
    public function passesWhenClassIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/ExactClass.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/SuppressedLongClass.php'],
            [],
        );
    }

    #[Test]
    public function countsNonPublicMethodsByDefault(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/TooManyMethodsRule/ClassWithNonPublicMethods.php'],
            [
                ['Class ClassWithNonPublicMethods has 6 methods. Maximum allowed is 5.', 7],
            ],
        );
    }
}
