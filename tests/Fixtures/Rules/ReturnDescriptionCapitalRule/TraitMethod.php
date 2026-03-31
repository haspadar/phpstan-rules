<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ReturnDescriptionCapitalRule;

trait TraitMethod
{
    /**
     * Returns something.
     *
     * @return string the user's name
     */
    public function getName(): string
    {
        return '';
    }
}
