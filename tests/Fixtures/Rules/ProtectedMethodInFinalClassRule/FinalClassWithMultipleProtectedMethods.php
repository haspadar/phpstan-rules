<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ProtectedMethodInFinalClassRule;

final class FinalClassWithMultipleProtectedMethods
{
    protected function first(): void
    {
    }

    protected function second(): void
    {
    }
}
