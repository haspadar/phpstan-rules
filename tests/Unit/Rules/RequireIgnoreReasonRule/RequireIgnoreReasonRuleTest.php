<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\RequireIgnoreReasonRule;

use Haspadar\PHPStanRules\Rules\RequireIgnoreReasonRule;
use PhpParser\Comment\Doc;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Node\FileNode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Integration test for RequireIgnoreReasonRule.
 *
 * RuleTestCase cannot be used because PHPStan's LocalIgnoresProcessor would
 * emit "no error to ignore" errors for the placeholder identifiers inside
 * fixtures that the isolated rule registry cannot satisfy. Instead the rule
 * is driven directly: we build a FileNode carrying a hand-crafted Comment
 * and assert the list of RuleError instances it returns.
 */
final class RequireIgnoreReasonRuleTest extends TestCase
{
    #[Test]
    public function reportsSuppressWithoutReason(): void
    {
        $errors = $this->runOn('/** @phpstan-ignore missingType.iterableValue */');

        self::assertCount(
            1,
            $errors,
            'A bare @phpstan-ignore must yield exactly one error',
        );
    }

    #[Test]
    public function reportsSuppressWithTooShortReason(): void
    {
        $errors = $this->runOn('/** @phpstan-ignore missingType.iterableValue (x) */');

        self::assertSame(
            'Suppress "missingType.iterableValue" must include a reason in parentheses: @phpstan-ignore missingType.iterableValue (reason).',
            $errors[0]->getMessage(),
            'A too-short reason must yield the same error as a missing one',
        );
    }

    #[Test]
    public function reportsPsalmSuppressWithoutReason(): void
    {
        $errors = $this->runOn('/** @psalm-suppress UnusedVariable */');

        self::assertSame(
            'Suppress "UnusedVariable" must include a reason after "--": @psalm-suppress UnusedVariable -- reason.',
            $errors[0]->getMessage(),
            'Psalm suppress without reason must yield a Psalm-style error message',
        );
    }

    #[Test]
    public function passesSuppressWithReason(): void
    {
        $errors = $this->runOn('/** @phpstan-ignore missingType.iterableValue (documented upstream) */');

        self::assertSame(
            [],
            $errors,
            'A @phpstan-ignore with a parenthesised reason must yield no errors',
        );
    }

    #[Test]
    public function passesWhenIdentifierIsInAllowList(): void
    {
        $rule = new RequireIgnoreReasonRule(['allowedBareIdentifiers' => ['project.wide']]);
        $node = $this->fileNodeWithDocComment('/** @phpstan-ignore project.wide */');
        $scope = $this->createStub(Scope::class);

        self::assertSame(
            [],
            $rule->processNode($node, $scope),
            'An identifier in allowedBareIdentifiers must not be reported even without a reason',
        );
    }

    #[Test]
    public function returnsEmptyListWhenFileHasNoSuppresses(): void
    {
        $errors = $this->runOn('/** Regular docblock summary. */');

        self::assertSame(
            [],
            $errors,
            'A file without suppress annotations must pass',
        );
    }

    /**
     * Runs the rule on a synthetic FileNode whose expression carries a single docblock.
     *
     * @return array<int, \PHPStan\Rules\IdentifierRuleError>
     */
    private function runOn(string $commentText): array
    {
        $rule = new RequireIgnoreReasonRule();
        $node = $this->fileNodeWithDocComment($commentText);
        $scope = $this->createStub(Scope::class);

        return $rule->processNode($node, $scope);
    }

    /**
     * Builds a FileNode containing a statement whose doc comment is the given text.
     */
    private function fileNodeWithDocComment(string $commentText): FileNode
    {
        $expression = new Expression(new String_(''), ['comments' => [new Doc($commentText)]]);

        return new FileNode([$expression]);
    }
}
