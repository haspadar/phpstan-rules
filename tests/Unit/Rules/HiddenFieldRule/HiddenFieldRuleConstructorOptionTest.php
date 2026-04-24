<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\HiddenFieldRule;

use Haspadar\PHPStanRules\Rules\HiddenFieldRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<HiddenFieldRule> */
final class HiddenFieldRuleConstructorOptionTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new HiddenFieldRule(
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\LocalAssignmentCollector(),
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\ParamShadowDetector(),
            ['ignoreConstructorParameter' => false],
        );
    }

    #[Test]
    public function reportsNonPromotedConstructorParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/ConstructorWithShadowOptOut.php'],
            [
                [
                    'Parameter $name in Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\HiddenFieldRule\\ConstructorWithShadowOptOut::__construct() shadows property of the same name. Rename to avoid the name collision.',
                    11,
                ],
            ],
            'Disabling ignoreConstructorParameter surfaces shadow on non-promoted constructor params',
        );
    }

    #[Test]
    public function passesForPromotedConstructorParameterEvenWithOptOut(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/PromotedConstructor.php'],
            [],
            'Promoted parameters are never shadows regardless of ignoreConstructorParameter',
        );
    }
}
