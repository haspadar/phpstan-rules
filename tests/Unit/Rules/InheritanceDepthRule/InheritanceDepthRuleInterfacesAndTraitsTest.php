<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InheritanceDepthRule;

use Haspadar\PHPStanRules\Rules\InheritanceDepthRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Proves that interfaces (`implements`) and traits (`use`) never contribute to the depth.
 *
 * @extends RuleTestCase<InheritanceDepthRule>
 */
final class InheritanceDepthRuleInterfacesAndTraitsTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InheritanceDepthRule($this->createReflectionProvider(), maxDepth: 0);
    }

    #[Test]
    public function doesNotCountInterfacesOrTraits(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/InterfacesAndTraits/ImplementsAndUses.php'],
            [],
            'Class implementing interfaces and using traits but without extends must have depth 0',
        );
    }
}
