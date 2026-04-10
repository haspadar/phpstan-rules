<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CatchParameterNameRule;

final class TooLongName
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\Throwable $veryverylongname) {
            echo $veryverylongname->getMessage();
        }
    }
}
