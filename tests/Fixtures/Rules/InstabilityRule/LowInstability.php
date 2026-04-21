<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\InstabilityRule\LowInstability;

final class Stable
{
    public function ping(): string
    {
        return 'pong';
    }
}

final class C01 { public function use(Stable $s): string { return $s->ping(); } }
final class C02 { public function use(Stable $s): string { return $s->ping(); } }
final class C03 { public function use(Stable $s): string { return $s->ping(); } }
final class C04 { public function use(Stable $s): string { return $s->ping(); } }
final class C05 { public function use(Stable $s): string { return $s->ping(); } }
