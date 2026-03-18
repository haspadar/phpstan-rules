<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLinesRule;

final class InlineHashCommentsMethod
{
    public function run(): string
    {
        $brand = 'Orbit'; # source brand
        $model = 'Desk'; # source model
        $route = $brand . ', ' . $model; # compose route
        $label = strtoupper($route); # make label

        return $label;
    }
}
