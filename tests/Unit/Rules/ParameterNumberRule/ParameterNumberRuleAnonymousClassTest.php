<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\ParameterNumberRule;

use Haspadar\PHPStanRules\Rules\ParameterNumberRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<ParameterNumberRule> */
final class ParameterNumberRuleAnonymousClassTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new ParameterNumberRule(3);
    }

    #[Test]
    public function reportsErrorForAnonymousClassMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/ParameterNumberRule/AnonymousClassLongMethod.php'],
            [
                [
                    'Method AnonymousClass771e1210c6a01d4ab7c9052b0a6987ff::create() has 4 parameters. Maximum allowed is 3.',
                    8,
                ],
            ],
        );
    }
}
