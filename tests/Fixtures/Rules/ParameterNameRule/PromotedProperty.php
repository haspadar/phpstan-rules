<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParameterNameRule;

final class PromotedProperty
{
    public function __construct(private string $nm)
    {
    }

    public function run(): string
    {
        return $this->nm;
    }
}
