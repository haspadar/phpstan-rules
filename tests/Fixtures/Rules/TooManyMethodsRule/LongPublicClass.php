<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TooManyMethodsRule;

final class LongPublicClass
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

    private function secret(): void
    {
    }
}
