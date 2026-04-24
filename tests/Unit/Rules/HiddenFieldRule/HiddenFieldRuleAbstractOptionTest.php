<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\HiddenFieldRule;

use Haspadar\PHPStanRules\Rules\HiddenFieldRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<HiddenFieldRule> */
final class HiddenFieldRuleAbstractOptionTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new HiddenFieldRule(
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\LocalAssignmentCollector(),
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\ParamShadowDetector(),
            ['ignoreAbstractMethods' => true],
        );
    }

    #[Test]
    public function passesWhenAbstractMethodsAreIgnored(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/AbstractMethodWithParamShadow.php'],
            [],
            'Enabling ignoreAbstractMethods must suppress reports on abstract declarations',
        );
    }
}
