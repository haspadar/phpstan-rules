<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocEmptyMethodRule;

trait TraitWithEmptyMethodPhpDoc
{
    /** */
    public function save(): void
    {
    }
}
