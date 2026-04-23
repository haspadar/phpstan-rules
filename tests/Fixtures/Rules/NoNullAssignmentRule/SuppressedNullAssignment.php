<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoNullAssignmentRule;

final class SuppressedNullAssignment
{
    public function demo(): void
    {
        /** @phpstan-ignore haspadar.noNullAssignment */
        $legacyCompat = null;
    }
}
