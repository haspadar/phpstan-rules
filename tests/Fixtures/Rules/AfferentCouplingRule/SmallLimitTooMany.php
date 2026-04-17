<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\SmallLimitTooMany;

final class HotTarget
{
    public function ping(): string
    {
        return 'pong';
    }
}

final class ConsumerOne
{
    public function use(HotTarget $target): string
    {
        return $target->ping();
    }
}

final class ConsumerTwo
{
    public function use(HotTarget $target): string
    {
        return $target->ping();
    }
}

final class ConsumerThree
{
    public function use(HotTarget $target): string
    {
        return $target->ping();
    }
}
