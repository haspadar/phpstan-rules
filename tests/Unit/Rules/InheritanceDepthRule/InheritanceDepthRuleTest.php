<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\InheritanceDepthRule;

use Haspadar\PHPStanRules\Rules\InheritanceDepthRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<InheritanceDepthRule> */
final class InheritanceDepthRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new InheritanceDepthRule($this->createReflectionProvider(), maxDepth: 2);
    }

    #[Test]
    public function passesWhenClassHasNoParent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/Shallow/ShallowClass.php'],
            [],
            'Class with no parent must have depth 0 and must not be reported',
        );
    }

    #[Test]
    public function passesWhenClassIsExactlyAtLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/AtLimit/AtLimitLevelTwo.php'],
            [],
            'Class whose depth equals the limit must not be reported',
        );
    }

    #[Test]
    public function reportsClassDeeperThanLimit(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/AtLimit/AtLimitLeaf.php'],
            [
                [
                    'Class AtLimitLeaf has inheritance depth 3 which exceeds the allowed 2.',
                    7,
                ],
            ],
            'Class whose depth exceeds the limit must be reported on the line of its declaration',
        );
    }

    #[Test]
    public function suppressesErrorWhenPhpstanIgnorePresent(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/InheritanceDepthRule/SuppressedTooDeep/SuppressedTooDeepLeaf.php'],
            [],
            '@phpstan-ignore haspadar.inheritanceDepth must suppress the error',
        );
    }
}
