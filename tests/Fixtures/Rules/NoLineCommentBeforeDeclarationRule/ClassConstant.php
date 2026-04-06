<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

final class ClassConstant
{
    // line comment before constant — not checked by this rule
    public const VERSION = '1.0';
}
