<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoActorSuffixRule;

use Haspadar\PHPStanRules\Rules\NoActorSuffixRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoActorSuffixRule> */
final class NoActorSuffixRuleExcludedClassesTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new NoActorSuffixRule(
            $this->createReflectionProvider(),
            [
                'allowedWords' => [],
                'excludedParentNamespaces' => [],
                'excludedClasses' => [
                    'Haspadar\\PHPStanRules\\Tests\\Fixtures\\Rules\\NoActorSuffixRule\\ExcludedLegacyManager',
                ],
            ],
        );
    }

    #[Test]
    public function passesWhenFullyQualifiedClassNameIsExcluded(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/ExcludedLegacyManager.php'],
            [],
            'A class whose FQCN is in excludedClasses must be skipped unconditionally',
        );
    }

    #[Test]
    public function reportsWhenFullyQualifiedClassNameIsNotInExcludedList(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/UserDispatcher.php'],
            [
                [
                    'Class UserDispatcher must not end with actor suffix \'Dispatcher\'. Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.',
                    7,
                ],
            ],
            'A class whose FQCN is not in excludedClasses must be reported',
        );
    }
}
