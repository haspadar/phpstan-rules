<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ModifiedControlVariableRule;

final class SuppressedClass
{
    public function process(): void
    {
        for ($i = 0; $i < 10; $i++) {
            /** @phpstan-ignore haspadar.modifiedControlVar */
            $i++;
        }
    }
}
