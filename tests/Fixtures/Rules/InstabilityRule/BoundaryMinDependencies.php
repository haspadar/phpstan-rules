<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\BoundaryMinDependencies;

final class Boundary
{
    public function run(DepOne $a, DepTwo $b, DepThree $c, DepFour $d): string
    {
        return $a->ping() . $b->ping() . $c->ping() . $d->ping();
    }
}

final class Consumer
{
    public function consume(Boundary $b): string
    {
        return 'x';
    }
}

final class DepOne { public function ping(): string { return 'a'; } }
final class DepTwo { public function ping(): string { return 'b'; } }
final class DepThree { public function ping(): string { return 'c'; } }
final class DepFour { public function ping(): string { return 'd'; } }
