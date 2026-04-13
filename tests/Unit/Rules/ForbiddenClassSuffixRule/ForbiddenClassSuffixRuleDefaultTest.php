<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ForbiddenClassSuffixRule;

use Haspadar\PHPStanRules\Rules\ForbiddenClassSuffixRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ForbiddenClassSuffixRule> */
final class ForbiddenClassSuffixRuleDefaultTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ForbiddenClassSuffixRule();
    }

    #[Test]
    public function reportsErrorWithDefaultSuffixes(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/ClassWithManagerSuffix.php'],
            [
                [
                    "Class UserManager uses forbidden suffix 'Manager'. Rename to describe its responsibility.",
                    7,
                ],
            ],
        );
    }

    #[Test]
    public function passesWithDefaultSuffixesWhenClean(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ForbiddenClassSuffixRule/ClassWithCleanName.php'],
            [],
        );
    }
}
