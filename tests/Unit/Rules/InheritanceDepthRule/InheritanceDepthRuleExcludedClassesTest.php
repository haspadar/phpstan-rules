<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InheritanceDepthRule;

use Haspadar\PHPStanRules\Rules\InheritanceDepthRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<InheritanceDepthRule> */
final class InheritanceDepthRuleExcludedClassesTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InheritanceDepthRule(
            $this->createReflectionProvider(),
            maxDepth: 2,
            options: [
                'excludedClasses' => [
                    'Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\InheritanceDepthRule\\ExcludedTarget\\ExcludedDeepLeaf',
                ],
            ],
        );
    }

    #[Test]
    public function skipsClassListedInExcludedClasses(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/ExcludedTarget/ExcludedDeepLeaf.php'],
            [],
            'Class in excludedClasses must never be reported even when depth exceeds the limit',
        );
    }

    #[Test]
    public function stillReportsOtherClassesWhenExcludedClassesConfigured(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/AtLimit/AtLimitLeaf.php'],
            [
                [
                    'Class AtLimitLeaf has inheritance depth 3 which exceeds the allowed 2.',
                    7,
                ],
            ],
            'Classes not listed in excludedClasses must still be reported',
        );
    }
}
