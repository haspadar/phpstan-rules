<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLinesRule;

final class BlockCommentLinesMethod
{
    public function run(): string
    {
        /* load brand */
        $brand = 'Orbit';
        /* load model */
        $model = 'Desk';
        /* build label */
        $route = $brand . ', ' . $model;

        return strtoupper($route);
    }
}
