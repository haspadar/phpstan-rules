<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\FinalClassRule;

final class AnonymousClass
{
    public function make(): object
    {
        return new class () {
        };
    }
}
