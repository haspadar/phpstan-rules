<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\NoActorSuffixRule;

use Haspadar\PHPStanRules\Rules\NoActorSuffixRule;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;

/** @extends RuleTestCase<NoActorSuffixRule> */
final class NoActorSuffixRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new NoActorSuffixRule(
            $this->createReflectionProvider(),
            [
                'allowedWords' => [
                    'User',
                    'Order',
                    'Number',
                    'Member',
                    'Owner',
                    'Customer',
                    'Folder',
                    'Header',
                    'Footer',
                    'Buffer',
                    'Layer',
                    'Marker',
                    'Parameter',
                    'Character',
                    'Identifier',
                    'Integer',
                    'Author',
                    'Visitor',
                    'Error',
                    'Color',
                    'Vendor',
                    'Vector',
                    'Factor',
                    'Actor',
                    'Director',
                    'Ancestor',
                    'Descriptor',
                ],
                'excludedParentNamespaces' => [
                    'Symfony\\',
                    'Illuminate\\',
                    'Doctrine\\',
                    'Laminas\\',
                    'Yii\\',
                    'Laravel\\',
                ],
                'excludedClasses' => [],
            ],
        );
    }

    #[Test]
    public function reportsActorSuffixEndingInEr(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/UserDispatcher.php'],
            [
                [
                    'Class UserDispatcher must not end with actor suffix \'Dispatcher\'. Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.',
                    7,
                ],
            ],
            'A class ending in -er whose last word is not in allowedWords must be reported',
        );
    }

    #[Test]
    public function reportsActorSuffixEndingInOr(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/UserOrchestrator.php'],
            [
                [
                    'Class UserOrchestrator must not end with actor suffix \'Orchestrator\'. Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.',
                    7,
                ],
            ],
            'A class ending in -or whose last word is not in allowedWords must be reported',
        );
    }

    #[Test]
    public function reportsAnotherActorSuffixEndingInOr(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/PaymentCoordinator.php'],
            [
                [
                    'Class PaymentCoordinator must not end with actor suffix \'Coordinator\'. Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.',
                    7,
                ],
            ],
            'Every class ending in -or must be reported individually',
        );
    }

    #[Test]
    public function reportsControllerWithoutFrameworkBase(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/StandaloneController.php'],
            [
                [
                    'Class StandaloneController must not end with actor suffix \'Controller\'. Classes are nouns, not procedures. Rename to a domain noun, or see README for when to extend allowedWords / excludedParentNamespaces.',
                    7,
                ],
            ],
            'A class named Controller without a framework base must be reported',
        );
    }

    #[Test]
    public function passesWhenLastWordIsInAllowedWords(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/User.php'],
            [],
            'A class whose name matches an entry in allowedWords must pass',
        );
    }

    #[Test]
    public function passesWhenLastPascalCaseWordIsInAllowedWords(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/OrderNumber.php'],
            [],
            'A compound class name must be split into PascalCase words and matched by the last one',
        );
    }

    #[Test]
    public function passesWhenLastWordIsHeader(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/HttpHeader.php'],
            [],
            'A compound class name ending with an allowed word must pass',
        );
    }

    #[Test]
    public function passesWhenClassNameDoesNotEndInErOrOr(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/Money.php'],
            [],
            'Classes without -er/-or suffix are outside this rule scope',
        );
    }

    #[Test]
    public function passesForAnonymousClasses(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/AnonymousClassCall.php'],
            [],
            'Anonymous classes are never reported',
        );
    }

    #[Test]
    public function passesWhenSuppressed(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/SuppressedManager.php'],
            [],
            'A @phpstan-ignore haspadar.noActorSuffix comment must silence the report',
        );
    }

    #[Test]
    public function passesForInterfacesEvenWithActorSuffix(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/PaymentHandler.php'],
            [],
            'Interfaces are not Class_ nodes and must never be reported',
        );
    }

    #[Test]
    public function passesForTraitsEvenWithActorSuffix(): void
    {
        $this->analyse(
            [__DIR__ . '/../../../Fixtures/Rules/NoActorSuffixRule/OrderCreationTrigger.php'],
            [],
            'Traits are not Class_ nodes and must never be reported',
        );
    }
}
