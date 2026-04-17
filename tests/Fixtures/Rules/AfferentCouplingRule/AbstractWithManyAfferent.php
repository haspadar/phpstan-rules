<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\AfferentCouplingRule\AbstractWithManyAfferent;

abstract class HotAbstract
{
    public function ping(): string
    {
        return 'pong';
    }
}

final class ConsumerAlpha
{
    public function use(HotAbstract $target): string
    {
        return $target->ping();
    }
}

final class ConsumerBeta
{
    public function use(HotAbstract $target): string
    {
        return $target->ping();
    }
}

final class ConsumerGamma
{
    public function use(HotAbstract $target): string
    {
        return $target->ping();
    }
}
