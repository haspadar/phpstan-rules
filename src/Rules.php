<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules;

/**
 * Entry point for PHPStan rules registration
 */
final class Rules
{
    /**
     * Returns the list of rule class names provided by this extension
     *
     * @return list<class-string>
     */
    public function all(): array
    {
        return [
            \Haspadar\PHPStanRules\Rules\MethodLinesRule::class,
        ];
    }
}
