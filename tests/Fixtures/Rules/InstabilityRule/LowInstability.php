<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\LowInstability;

final class Stable
{
    public function ping(Helper $h): string
    {
        return 'pong' . $h->tag();
    }
}

final class Helper
{
    public function tag(): string
    {
        return '!';
    }
}

final class C01 { public function use(Stable $s, Helper $h): string { return $s->ping($h); } }
final class C02 { public function use(Stable $s, Helper $h): string { return $s->ping($h); } }
final class C03 { public function use(Stable $s, Helper $h): string { return $s->ping($h); } }
final class C04 { public function use(Stable $s, Helper $h): string { return $s->ping($h); } }
