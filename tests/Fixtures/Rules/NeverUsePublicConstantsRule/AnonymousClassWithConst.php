<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NeverUsePublicConstantsRule;

/** Creates an anonymous class with a public constant. */
final class AnonymousClassWithConst
{
    /** Returns an anonymous class instance. */
    public function create(): object
    {
        return new class {
            public const string NAME = 'anon';
        };
    }
}
