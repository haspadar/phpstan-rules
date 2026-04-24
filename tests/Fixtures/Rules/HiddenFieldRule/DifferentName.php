<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class DifferentName
{
    private string $title = '';

    public function update(string $newTitle): void
    {
        $this->title = $newTitle;
    }
}
