<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class SetterMethod
{
    private string $title = '';

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
