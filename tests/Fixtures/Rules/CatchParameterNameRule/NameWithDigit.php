<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CatchParameterNameRule;

final class NameWithDigit
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\Throwable $ex1) {
            echo $ex1->getMessage();
        }
    }
}
