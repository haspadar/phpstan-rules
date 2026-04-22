<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProhibitStaticPropertiesRule;

final class ClassWithInstanceProperty
{
    private int $count = 0;

    public function next(): self
    {
        $copy = clone $this;
        $copy->count = $this->count + 1;
        return $copy;
    }
}
