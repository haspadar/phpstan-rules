<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\NoLineCommentBeforeDeclarationRule;

/** Class with PHPDoc */
final class PhpDocComments
{
    /** Some property */
    public string $name = 'test';

    /** Some method */
    public function foo(): void
    {
    }
}
