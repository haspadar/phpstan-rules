<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\PhpDocEmptyMethodRule;

final class ClassWithMethodTagsOnly
{
    /**
     * @return void
     */
    public function save(): void
    {
    }
}
