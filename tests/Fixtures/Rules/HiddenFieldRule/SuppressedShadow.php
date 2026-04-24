<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class SuppressedShadow
{
    private string $name = '';

    /** @phpstan-ignore haspadar.hiddenField */
    public function rename(string $name): void
    {
        $this->name = $name;
    }
}
