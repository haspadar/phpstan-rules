<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocMissingClassRule;

/** A class that creates an anonymous class without PHPDoc — should pass. */
final class AnonymousClass
{
    /** Returns an anonymous instance. */
    public function create(): object
    {
        return new class () {
        };
    }
}
