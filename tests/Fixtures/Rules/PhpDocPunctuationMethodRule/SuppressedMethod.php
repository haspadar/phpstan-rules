<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocPunctuationMethodRule;

final class SuppressedMethod
{
    /**
     * Saves the user
     *
     * @phpstan-ignore haspadar.phpdocPunctuation
     */
    public function save(): void
    {
    }
}
