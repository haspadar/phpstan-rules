<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithIrrelevantTagsOnly
{
    /**
     * Saves the user.
     *
     * @author John Doe
     *
     * @since 1.0
     */
    public function save(): void
    {
    }
}
