<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\HiddenFieldRule;

use Haspadar\PHPStanRules\Rules\HiddenFieldRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<HiddenFieldRule> */
final class HiddenFieldRuleIgnoreNamesTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new HiddenFieldRule(
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\LocalAssignmentCollector(),
            new \Haspadar\PHPStanRules\Rules\HiddenFieldRule\ParamShadowDetector(),
            ['ignoreNames' => ['value']],
        );
    }

    #[Test]
    public function passesWhenNameIsInIgnoreList(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/IgnoreNamesRespected.php'],
            [],
            'Names listed in ignoreNames must be skipped even when shadowing a property',
        );
    }

    #[Test]
    public function reportsWhenNameIsNotInIgnoreList(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/HiddenFieldRule/ParameterShadowsProperty.php'],
            [
                [
                    'Parameter $name in Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\HiddenFieldRule\\ParameterShadowsProperty::rename() shadows property of the same name. Rename to avoid the name collision.',
                    11,
                ],
            ],
            'ignoreNames limits to listed names only; other shadowing parameters still fail',
        );
    }
}
