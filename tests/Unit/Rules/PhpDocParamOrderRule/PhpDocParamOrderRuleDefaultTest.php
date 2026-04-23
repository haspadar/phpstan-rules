<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocParamOrderRule;

use Haspadar\PHPStanRules\Rules\PhpDocParamOrderRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocParamOrderRule> */
final class PhpDocParamOrderRuleDefaultTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new PhpDocParamOrderRule();
    }

    #[Test]
    public function reportsWrongOrderInPublicMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithWrongOrder.php'],
            [
                ['@param order for add() does not match the signature: expected $a, $b, got $b, $a.', 15],
            ],
            'Default options must still catch wrong @param order on public methods',
        );
    }

    #[Test]
    public function passesWhenPrivateMethodHasWrongOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithPrivateMethod.php'],
            [],
            'checkPublicOnly=true must skip private methods regardless of the @param order',
        );
    }

    #[Test]
    public function passesWhenOverriddenMethodHasWrongOrder(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamOrderRule/ClassWithOverriddenMethod.php'],
            [],
            'skipOverridden=true must skip #[Override] methods regardless of the @param order',
        );
    }
}
