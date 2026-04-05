<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

final class AnonymousClass
{
    public function foo(): object
    {
        return new class () {
            public function bar(): void
            {
            }
        };
    }
}
