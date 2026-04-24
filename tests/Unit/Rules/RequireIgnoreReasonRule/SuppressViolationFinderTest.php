<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit\Rules\RequireIgnoreReasonRule;

use Haspadar\PHPStanRules\Rules\RequireIgnoreReasonRule\SuppressViolationFinder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SuppressViolationFinderTest extends TestCase
{
    #[Test]
    public function acceptsPhpstanIgnoreWithReason(): void
    {
        $finder = new SuppressViolationFinder(5, []);

        self::assertSame(
            [],
            $finder->find('/** @phpstan-ignore missingType.iterableValue (documented in parent) */'),
            'A @phpstan-ignore with a parenthesised reason must produce no violations',
        );
    }

    #[Test]
    public function reportsPhpstanIgnoreWithoutReason(): void
    {
        $finder = new SuppressViolationFinder(5, []);

        self::assertSame(
            [['identifier' => 'missingType.iterableValue', 'offsetLine' => 0, 'kind' => 'phpstan']],
            $finder->find('/** @phpstan-ignore missingType.iterableValue */'),
            'A @phpstan-ignore missing the reason must report one violation',
        );
    }

    #[Test]
    public function reportsPhpstanIgnoreWithReasonShorterThanMinimum(): void
    {
        $finder = new SuppressViolationFinder(5, []);

        self::assertSame(
            [['identifier' => 'missingType.iterableValue', 'offsetLine' => 0, 'kind' => 'phpstan']],
            $finder->find('/** @phpstan-ignore missingType.iterableValue (x) */'),
            'A reason shorter than minReasonLength must be reported like a missing one',
        );
    }

    #[Test]
    public function acceptsPhpstanIgnoreNextLineWithReason(): void
    {
        $finder = new SuppressViolationFinder(5, []);

        self::assertSame(
            [],
            $finder->find('// @phpstan-ignore-next-line foo.bar (detailed reason)'),
            'The next-line variant honours the same reason rule',
        );
    }

    #[Test]
    public function acceptsPsalmSuppressWithReason(): void
    {
        $finder = new SuppressViolationFinder(5, []);

        self::assertSame(
            [],
            $finder->find('/** @psalm-suppress UnusedVariable -- reserved for plugin hook */'),
            'A @psalm-suppress followed by `-- reason` must produce no violations',
        );
    }

    #[Test]
    public function reportsPsalmSuppressWithoutReason(): void
    {
        $finder = new SuppressViolationFinder(5, []);

        self::assertSame(
            [['identifier' => 'UnusedVariable', 'offsetLine' => 0, 'kind' => 'psalm']],
            $finder->find('/** @psalm-suppress UnusedVariable */'),
            'A bare @psalm-suppress must be reported',
        );
    }

    #[Test]
    public function skipsIdentifiersFromAllowList(): void
    {
        $finder = new SuppressViolationFinder(5, ['foo.bar']);

        self::assertSame(
            [],
            $finder->find('/** @phpstan-ignore foo.bar */'),
            'Identifiers in allowedBareIdentifiers must be ignored even without a reason',
        );
    }

    #[Test]
    public function reportsIdentifiersNotInAllowList(): void
    {
        $finder = new SuppressViolationFinder(5, ['foo.bar']);

        self::assertSame(
            [['identifier' => 'other.identifier', 'offsetLine' => 0, 'kind' => 'phpstan']],
            $finder->find('/** @phpstan-ignore other.identifier */'),
            'allowedBareIdentifiers must not exempt identifiers outside the list',
        );
    }

    #[Test]
    public function returnsEmptyListWhenNoSuppressAnnotationsArePresent(): void
    {
        $finder = new SuppressViolationFinder(5, []);

        self::assertSame(
            [],
            $finder->find('/** Regular phpdoc summary with no suppress annotations */'),
            'A comment without suppress directives must produce no violations',
        );
    }

    #[Test]
    public function reportsOffsetLineOfSecondViolationInMultiLineComment(): void
    {
        $finder = new SuppressViolationFinder(5, []);
        $text = "/**\n * @phpstan-ignore first.id\n * @phpstan-ignore second.id\n */";

        self::assertSame(
            [
                ['identifier' => 'first.id', 'offsetLine' => 1, 'kind' => 'phpstan'],
                ['identifier' => 'second.id', 'offsetLine' => 2, 'kind' => 'phpstan'],
            ],
            $finder->find($text),
            'Each violation must carry the relative line offset inside the multi-line comment',
        );
    }

    #[Test]
    public function raisesMinimumRejectsOtherwiseValidReason(): void
    {
        $finder = new SuppressViolationFinder(50, []);

        self::assertSame(
            [['identifier' => 'foo.bar', 'offsetLine' => 0, 'kind' => 'phpstan']],
            $finder->find('/** @phpstan-ignore foo.bar (short reason) */'),
            'Raising minReasonLength must reject previously-acceptable reasons that are shorter',
        );
    }
}
