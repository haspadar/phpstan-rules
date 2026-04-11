<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\UnnecessaryLocalRule;

final class UnnecessaryBeforeThrow
{
    public function run(): void
    {
        $exception = new \RuntimeException('fail');
        throw $exception;
    }
}
