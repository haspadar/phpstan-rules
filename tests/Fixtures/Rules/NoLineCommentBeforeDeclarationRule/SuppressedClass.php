<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

/** @phpstan-ignore-next-line haspadar.noLineCommentBefore */
// Line comment before class
final class SuppressedClass
{
}
