<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\ExcludedUnstable;

final class ExcludedUnstable
{
    public function run(DepOne $a, DepTwo $b, DepThree $c, DepFour $d, DepFive $e, DepSix $f): string
    {
        return $a->ping() . $b->ping() . $c->ping() . $d->ping() . $e->ping() . $f->ping();
    }
}

final class Consumer
{
    public function use(ExcludedUnstable $u): string
    {
        return '';
    }
}

final class DepOne { public function ping(): string { return 'a'; } }
final class DepTwo { public function ping(): string { return 'b'; } }
final class DepThree { public function ping(): string { return 'c'; } }
final class DepFour { public function ping(): string { return 'd'; } }
final class DepFive { public function ping(): string { return 'e'; } }
final class DepSix { public function ping(): string { return 'f'; } }
