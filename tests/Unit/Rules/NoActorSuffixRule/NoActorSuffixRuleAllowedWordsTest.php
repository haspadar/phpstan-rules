<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoActorSuffixRule;

use Haspadar\PHPStanRules\Rules\NoActorSuffixRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoActorSuffixRule> */
final class NoActorSuffixRuleAllowedWordsTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new NoActorSuffixRule(
            $this->createReflectionProvider(),
            [
                'allowedWords' => ['User'],
                'excludedParentNamespaces' => [],
                'excludedClasses' => [],
            ],
        );
    }

    #[Test]
    public function reportsWhenLastWordIsNotInShortenedWhitelist(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/OrderNumber.php'],
            [
                [
                    'Class OrderNumber must not end with actor suffix \'Number\'. Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.',
                    7,
                ],
            ],
            'Removing Number from allowedWords must turn OrderNumber into a reported class',
        );
    }

    #[Test]
    public function passesWhenLastWordIsInShortenedWhitelist(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/User.php'],
            [],
            'A whitelist of just [User] must still pass User',
        );
    }
}
