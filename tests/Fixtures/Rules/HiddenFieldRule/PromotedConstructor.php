<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\HiddenFieldRule;

final class PromotedConstructor
{
    public function __construct(private string $name) {}
}
