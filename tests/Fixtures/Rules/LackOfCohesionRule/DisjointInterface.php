<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\LackOfCohesionRule;

interface DisjointInterface
{
    public function firstGroupOne(): int;

    public function firstGroupTwo(): int;

    public function secondGroupOne(): int;

    public function secondGroupTwo(): int;
}
