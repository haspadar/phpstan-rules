<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\ExcludedTarget;

final class ExcludedHotTarget
{
    public function ping(): string
    {
        return 'pong';
    }
}

final class ConsumerAlpha
{
    public function use(ExcludedHotTarget $target): string
    {
        return $target->ping();
    }
}

final class ConsumerBeta
{
    public function use(ExcludedHotTarget $target): string
    {
        return $target->ping();
    }
}

final class ConsumerGamma
{
    public function use(ExcludedHotTarget $target): string
    {
        return $target->ping();
    }
}
