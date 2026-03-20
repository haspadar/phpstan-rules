<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\TooManyMethodsRule;

final class ClassWithNonPublicMethods
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

    protected function protectedOne(): void
    {
    }

    private function privateOne(): void
    {
    }

    private function privateTwo(): void
    {
    }
}
