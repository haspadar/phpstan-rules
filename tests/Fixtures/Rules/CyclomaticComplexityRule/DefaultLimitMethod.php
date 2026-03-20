<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\CyclomaticComplexityRule;

final class DefaultLimitMethod
{
    public function run(int $a, int $b, int $c, int $d, bool $e, bool $f): string
    {
        if ($a > 0) {
            if ($b > 0) {
                if ($c > 0) {
                    if ($d > 0) {
                        if ($e) {
                            if ($f) {
                                return 'six';
                            }

                            return 'five';
                        }

                        return 'four';
                    }

                    return 'three';
                }

                return 'two';
            }

            return 'one';
        }

        while ($a > 0) {
            $a--;
        }

        foreach ([] as $v) {
            if ($v > 0) {
                return 'loop';
            }
        }

        return 'none';
    }
}
