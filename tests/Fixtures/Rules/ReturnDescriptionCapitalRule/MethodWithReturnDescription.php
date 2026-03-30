<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnDescriptionCapitalRule;

final class MethodWithReturnDescription
{
    /**
     * Returns something.
     *
     * @return string The user's name
     */
    public function getName(): string
    {
        return '';
    }
}
