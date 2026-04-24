<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoActorSuffixRule;

use Haspadar\PHPStanRules\Rules\NoActorSuffixRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoActorSuffixRule> */
final class NoActorSuffixRuleExcludedParentNamespacesTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new NoActorSuffixRule(
            $this->createReflectionProvider(),
            [
                'allowedWords' => [],
                'excludedParentNamespaces' => ['Symfony\\', 'Illuminate\\'],
                'excludedClasses' => [],
            ],
        );
    }

    #[Test]
    public function passesWhenParentClassLivesUnderExcludedNamespace(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/VendorStubs/AbstractController.php',
                __DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/SymfonyStyleController.php',
            ],
            [],
            'A class whose parent lives under Symfony\\ must be skipped',
        );
    }

    #[Test]
    public function passesWhenImplementedInterfaceLivesUnderExcludedNamespace(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/VendorStubs/ShouldQueue.php',
                __DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/LaravelStyleController.php',
            ],
            [],
            'A class implementing an interface under Illuminate\\ must be skipped',
        );
    }

    #[Test]
    public function passesWhenTransitiveAncestorLivesUnderExcludedNamespace(): void
    {
        $this->analyse(
            [
                __DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/VendorStubs/AbstractController.php',
                __DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/AppBaseController.php',
                __DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/TransitiveFrameworkController.php',
            ],
            [],
            'A class whose grandparent lives under Symfony\\ must be skipped via transitive reflection',
        );
    }

    #[Test]
    public function reportsWhenNoAncestorMatchesExcludedNamespaces(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/UserDispatcher.php'],
            [
                [
                    'Class UserDispatcher must not end with actor suffix "Dispatcher". Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.',
                    7,
                ],
            ],
            'A class without framework-managed ancestors must still be reported',
        );
    }
}
