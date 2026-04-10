<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\VariableNameRule;

final class CatchVariable
{
    public function run(): void
    {
        try {
            echo 'ok';
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}
