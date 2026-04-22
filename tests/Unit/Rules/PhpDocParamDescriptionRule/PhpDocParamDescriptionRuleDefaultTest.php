<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\PhpDocParamDescriptionRule;

use Haspadar\PHPStanRules\Rules\PhpDocParamDescriptionRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<PhpDocParamDescriptionRule> */
final class PhpDocParamDescriptionRuleDefaultTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new PhpDocParamDescriptionRule();
    }

    #[Test]
    public function reportsEmptyDescriptionInPublicMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithEmptyParamDescription.php'],
            [
                ['@param $name for greet() is missing a description.', 14],
            ],
            'Default options must still catch empty @param descriptions on public methods',
        );
    }

    #[Test]
    public function passesWhenPrivateMethodHasEmptyDescription(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithPrivateMethod.php'],
            [],
            'checkPublicOnly=true must skip private methods regardless of empty descriptions',
        );
    }

    #[Test]
    public function passesWhenOverriddenMethodHasEmptyDescription(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/PhpDocParamDescriptionRule/ClassWithOverriddenMethod.php'],
            [],
            'skipOverridden=true must skip #[Override] methods regardless of empty descriptions',
        );
    }
}
