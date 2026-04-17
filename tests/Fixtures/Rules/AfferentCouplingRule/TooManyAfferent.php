<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\TooManyAfferent;

final class HotTarget
{
    public function ping(): string
    {
        return 'pong';
    }
}

final class C01 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C02 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C03 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C04 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C05 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C06 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C07 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C08 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C09 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C10 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C11 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C12 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C13 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C14 { public function use(HotTarget $t): string { return $t->ping(); } }
final class C15 { public function use(HotTarget $t): string { return $t->ping(); } }
