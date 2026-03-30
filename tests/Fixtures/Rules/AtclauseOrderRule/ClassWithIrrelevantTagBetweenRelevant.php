<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AtclauseOrderRule;

final class ClassWithIrrelevantTagBetweenRelevant
{
    /**
     * Saves the user.
     *
     * @throws \RuntimeException When saving fails.
     *
     * @author John Doe
     *
     * @return void
     */
    public function save(): void
    {
    }
}
