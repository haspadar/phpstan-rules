<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\PhpDoc;

/**
 * Extracts the summary line from a PHPDoc comment text.
 * The summary is the first non-empty line after the opening delimiter,
 * provided it does not start with a PHPDoc tag (`@`). Lines starting
 * with `@` indicate a tags-only block with no summary.
 */
final class SummaryExtractor
{
    /**
     * Returns the trimmed summary line from the given PHPDoc text,
     * or null if no summary is present (tags-only or empty block)
     */
    public static function extract(string $docText): ?string
    {
        $lines = explode("\n", $docText);

        foreach ($lines as $line) {
            $trimmed = trim($line);
            $trimmed = preg_replace('#^/\*\*\s*#', '', $trimmed) ?? $trimmed;
            $trimmed = preg_replace('#\s*\*/$#', '', $trimmed) ?? $trimmed;
            $trimmed = preg_replace('#^\*\s?#', '', $trimmed) ?? $trimmed;
            $trimmed = trim($trimmed);

            if ($trimmed === '' || $trimmed === '*') {
                continue;
            }

            if (str_starts_with($trimmed, '@')) {
                return null;
            }

            return $trimmed;
        }

        return null;
    }
}
