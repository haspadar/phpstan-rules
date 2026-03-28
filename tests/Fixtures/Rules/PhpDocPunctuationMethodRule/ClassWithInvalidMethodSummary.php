<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocPunctuationMethodRule;

final class ClassWithInvalidMethodSummary
{
    /** Saves the user */
    public function save(): void
    {
    }
}
