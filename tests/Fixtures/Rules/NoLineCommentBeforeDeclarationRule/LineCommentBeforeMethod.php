<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

final class LineCommentBeforeMethod
{
    // Line comment before method
    public function foo(): void
    {
    }
}
