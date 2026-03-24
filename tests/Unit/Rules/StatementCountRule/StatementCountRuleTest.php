<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\StatementCountRule;

use Haspadar\PHPStanRules\Rules\StatementCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<StatementCountRule> */
final class StatementCountRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new StatementCountRule(5);
    }

    #[Test]
    public function passesWhenMethodFitsWithinLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/ShortMethod.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodExceedsLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/LongMethod.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\StatementCountRule\LongMethod::run() has 6 executable statements. Maximum allowed is 5.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenMethodIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/ExactMethod.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/SuppressedMethod.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenStatementsAreInsideClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithClosure.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenStatementsAreInsideArrowFunction(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithArrowFunction.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenStatementsAreInsideAnonymousClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithAnonymousClass.php'],
            [],
        );
    }

    #[Test]
    public function countsNestedStatementsRecursively(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/StatementCountRule/MethodWithNestedIf.php'],
            [],
        );
    }
}
