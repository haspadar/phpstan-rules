<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InheritanceDepthRule;

use Haspadar\PHPStanRules\Rules\InheritanceDepthRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<InheritanceDepthRule> */
final class InheritanceDepthRuleDefaultLimitTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InheritanceDepthRule($this->createReflectionProvider());
    }

    #[Test]
    public function passesWhenClassIsAtDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/AtLimit/AtLimitLeaf.php'],
            [],
            'Class with depth 3 must not be reported under the default limit of 3',
        );
    }

    #[Test]
    public function reportsClassExceedingDefaultLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/TooDeep/TooDeepLeaf.php'],
            [
                [
                    'Class TooDeepLeaf has inheritance depth 4 which exceeds the allowed 3.',
                    7,
                ],
            ],
            'Class with depth 4 must be reported under the default limit of 3',
        );
    }
}
