<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnDescriptionCapitalRule;

final class MethodWithoutReturnDescription
{
    /**
     * Returns something.
     *
     * @return string
     */
    public function getName(): string
    {
        return '';
    }
}
