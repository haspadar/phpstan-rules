<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\MissingThrowsRule;

use Haspadar\PHPStanRules\Rules\MissingThrowsRule;
use Override;
use PHPStan\Rules\Exceptions\MissingCheckedExceptionInThrowsCheck;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<MissingThrowsRule> */
final class MissingThrowsRuleSkipOverriddenDisabledTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new MissingThrowsRule(
            self::getContainer()->getByType(MissingCheckedExceptionInThrowsCheck::class),
            ['skipOverridden' => false],
        );
    }

    #[Test]
    public function reportsOverriddenMethodThrowWithoutDeclaration(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/ParentWithRunMethod.php',
                __DIR__ . '/../../../Fixtures/Rules/MissingThrowsRule/OverriddenMethodWithoutDeclaration.php',
            ],
            [
                [
                    "Method Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\MissingThrowsRule\\OverriddenMethodWithoutDeclaration::run() throws checked exception RuntimeException but it's missing from the PHPDoc @throws tag.",
                    14,
                ],
            ],
            'With skipOverridden disabled, an overridden method must be reported like any other',
        );
    }
}
