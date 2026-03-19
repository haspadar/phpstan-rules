<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TooManyMethodsRule;

/** @phpstan-ignore haspadar.tooManyMethods */
final class SuppressedLongClass
{
    public function one(): void
    {
    }

    public function two(): void
    {
    }

    public function three(): void
    {
    }

    public function four(): void
    {
    }

    public function five(): void
    {
    }

    public function six(): void
    {
    }

    public function seven(): void
    {
    }
}
