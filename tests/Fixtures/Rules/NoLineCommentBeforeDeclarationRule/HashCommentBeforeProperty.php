<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

final class HashCommentBeforeProperty
{
    # Hash comment before property
    public string $name = 'test';
}
