<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnDescriptionCapitalRule;

final class SuppressedMethod
{
    /**
     * Returns something.
     *
     * @return string the user's name
     */
    // @phpstan-ignore haspadar.returnCapital
    public function getName(): string
    {
        return '';
    }
}
