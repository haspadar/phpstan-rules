<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Fixtures\Rules\MethodLengthRule;

final class InlineSlashCommentsMethod
{
    public function run(): string
    {
        $title = 'draft'; // keep title
        $status = 'ready'; // keep status
        $summary = $title . $status; // build summary
        $slug = strtolower($summary); // normalize slug

        return $slug;
    }
}
