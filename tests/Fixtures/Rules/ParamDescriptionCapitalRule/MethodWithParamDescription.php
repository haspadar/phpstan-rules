<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

final class MethodWithParamDescription
{
    /**
     * Returns something based on the name.
     *
     * @param string $name User name to process
     *
     * @return string
     */
    public function getName(string $name): string
    {
        return $name;
    }
}
