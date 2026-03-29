<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocEmptyMethodRule;

final class SuppressedMethod
{
    /**
     * @phpstan-ignore haspadar.phpdocEmpty
     */
    public function save(): void
    {
    }
}
