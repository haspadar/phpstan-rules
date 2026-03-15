<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLinesRule;

final class SlashCommentLinesMethod
{
    public function run(): string
    {
        // load title
        $title = 'draft';
        // load status
        $status = 'ready';
        // build summary
        $summary = $title . $status;
        return strtolower($summary);
    }
}
