<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

/** PHPDoc before class */
final class MixedComments
{
    // Line comment before method
    public function foo(): void
    {
    }
}
