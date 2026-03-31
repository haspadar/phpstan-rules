<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

final class MethodWithoutParamDescription
{
    /**
     * Returns something based on the name.
     *
     * @param string $name
     *
     * @return string
     */
    public function getName(string $name): string
    {
        return $name;
    }
}
