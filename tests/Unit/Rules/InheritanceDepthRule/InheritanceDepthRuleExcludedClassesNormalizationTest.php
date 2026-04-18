<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InheritanceDepthRule;

use Haspadar\PHPStanRules\Rules\InheritanceDepthRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Verifies that excludedClasses entries are normalized: leading backslash stripped, case folded.
 *
 * @extends RuleTestCase<InheritanceDepthRule>
 */
final class InheritanceDepthRuleExcludedClassesNormalizationTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InheritanceDepthRule(
            $this->createReflectionProvider(),
            maxDepth: 2,
            options: [
                'excludedClasses' => [
                    '\\HASPADAR\\phpstanrules\\Tests\\Fixtures\\Rules\\InheritanceDepthRule\\ExcludedTarget\\excludedDEEPLeaf',
                ],
            ],
        );
    }

    #[Test]
    public function skipsClassWhenExcludedEntryHasLeadingBackslashAndMixedCase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/ExcludedTarget/ExcludedDeepLeaf.php'],
            [],
            'excludedClasses must match regardless of leading backslash or case differences',
        );
    }
}
