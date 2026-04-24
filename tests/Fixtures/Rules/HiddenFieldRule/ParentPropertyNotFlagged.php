<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class ParentPropertyNotFlagged extends ParentWithName
{
    public function rename(string $name): void
    {
        $this->name = $name;
    }
}
