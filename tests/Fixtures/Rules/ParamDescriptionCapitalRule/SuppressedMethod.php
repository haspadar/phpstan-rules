<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

final class SuppressedMethod
{
    /**
     * Returns something based on the name.
     *
     * @param string $name user name to process
     *
     * @return string
     */
    // @phpstan-ignore haspadar.paramCapital
    public function getName(string $name): string
    {
        return $name;
    }
}
