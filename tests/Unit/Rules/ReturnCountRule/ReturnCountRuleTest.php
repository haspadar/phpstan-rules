<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ReturnCountRule;

use Haspadar\PHPStanRules\Rules\ReturnCountRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ReturnCountRule> */
final class ReturnCountRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ReturnCountRule(1);
    }

    #[Test]
    public function passesWhenMethodHasOneReturn(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/MethodWithOneReturn.php'],
            [],
        );
    }

    #[Test]
    public function reportsErrorWhenMethodHasTwoReturns(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/MethodWithTwoReturns.php'],
            [
                [
                    'Method Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnCountRule\MethodWithTwoReturns::find() has 2 return statements. Maximum allowed is 1.',
                    9,
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenReturnIsInsideClosure(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/MethodWithReturnInClosure.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenReturnIsInsideArrowFunction(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/MethodWithReturnInArrowFunction.php'],
            [],
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/SuppressedMethodWithTwoReturns.php'],
            [],
        );
    }

    #[Test]
    public function passesWhenMethodIsAbstract(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ReturnCountRule/AbstractMethodClass.php'],
            [],
        );
    }
}
