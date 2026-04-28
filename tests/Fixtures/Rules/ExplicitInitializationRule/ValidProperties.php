<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ExplicitInitializationRule;

final class ValidProperties
{
    private ?string $name;

    private int $maxLines = 100;

    private string $prefix = 'app_';

    private bool $enabled = true;

    private float $ratio = 1.5;

    private int $count = 0;

    private float $zero = 0.0;

    private bool $active = false;

    private string $empty = '';

    /** @var string[] */
    private array $items = [];
}
