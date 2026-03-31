<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ParamDescriptionCapitalRule;

final class MethodWithMultipleParams
{
    /**
     * Returns something based on the name and age.
     *
     * @param string $name User name to process
     * @param int    $age  age of the user
     *
     * @return string
     */
    public function getName(string $name, int $age): string
    {
        return $name . $age;
    }
}
