<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InheritanceDepthRule;

use Haspadar\PHPStanRules\Rules\InheritanceDepthRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Proves that anonymous classes are skipped even when they extend a base class.
 *
 * @extends RuleTestCase<InheritanceDepthRule>
 */
final class InheritanceDepthRuleAnonymousTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InheritanceDepthRule($this->createReflectionProvider(), maxDepth: 0);
    }

    #[Test]
    public function skipsAnonymousClass(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/Anonymous/AnonymousFactory.php'],
            [],
            'Anonymous class with extends must be skipped even when its depth exceeds the limit',
        );
    }
}
