<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\ConstantUsageRule;

final class ClassWithArrayKeys
{
    /**
     * @return array<string, string>
     */
    public function config(string $host, int $port): array
    {
        return ['host' => $host, 'port' => (string) $port];
    }
}
