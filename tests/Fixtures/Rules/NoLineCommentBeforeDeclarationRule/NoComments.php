<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

final class NoComments
{
    public string $name = 'test';

    public function foo(): void
    {
    }
}
