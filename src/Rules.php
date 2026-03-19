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
            Rules\MethodLengthRule::class,
            Rules\FileLengthRule::class,
            Rules\TooManyMethodsRule::class,
        ];
    }
}
