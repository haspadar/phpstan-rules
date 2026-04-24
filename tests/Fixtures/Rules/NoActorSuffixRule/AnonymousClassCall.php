<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoActorSuffixRule;

final class AnonymousClassCall
{
    public function make(): object
    {
        return new class {
            public function run(): void
            {
            }
        };
    }
}
