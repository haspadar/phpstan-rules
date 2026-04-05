<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

/* Block comment before class */
final class BlockComments
{
    /* Block comment before property */
    public string $name = 'test';

    /* Block comment before method */
    public function foo(): void
    {
    }
}
