<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ExplicitInitializationRule;

final class PrimitiveProperties
{
    private int $count = 0;

    private float $ratio = 0.0;

    private bool $active = false;

    private string $name = '';
}
