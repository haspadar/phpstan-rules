<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules\RequireIgnoreReasonRule;

/**
 * Locates suppress annotations inside a comment and returns those lacking a valid reason.
 *
 * Supported forms: PHPStan "at-phpstan-ignore" plus its next-line and line variants
 * with an optional parenthesised reason, and Psalm "at-psalm-suppress" with an
 * optional "-- reason" tail borrowed from the ESLint convention.
 *
 * A reason is accepted when it is non-null and its trimmed length is at least the
 * configured minimum. Identifiers in the allow-list are skipped regardless of reason.
 */
final readonly class SuppressViolationFinder
{
    private const int IDENTIFIER_GROUP = 1;

    private const int REASON_GROUP = 2;

    /**
     * Constructs the finder with the minimum reason length and a whitelist of bare identifiers.
     *
     * @param int $minReasonLength Minimum trimmed length of an acceptable reason
     * @param list<string> $allowedBareIdentifiers Identifiers that may omit the reason
     */
    public function __construct(
        private int $minReasonLength,
        private array $allowedBareIdentifiers,
    ) {}

    /**
     * Returns a list of suppress annotations whose reason is missing or too short.
     *
     * Each returned tuple carries the identifier, the line offset inside the
     * searched text, and a "kind" (`phpstan` or `psalm`) so the caller can
     * build the correct error message for the relevant tool.
     *
     * @param string $text Raw comment text to scan for suppress annotations
     * @return list<array{identifier: string, offsetLine: int, kind: 'phpstan'|'psalm'}>
     */
    public function find(string $text): array
    {
        $violations = [];

        foreach ($this->matchPhpstanIgnore($text) as [$identifier, $reason, $offsetLine]) {
            if ($this->isAcceptable($identifier, $reason)) {
                continue;
            }

            $violations[] = ['identifier' => $identifier, 'offsetLine' => $offsetLine, 'kind' => 'phpstan'];
        }

        foreach ($this->matchPsalmSuppress($text) as [$identifier, $reason, $offsetLine]) {
            if ($this->isAcceptable($identifier, $reason)) {
                continue;
            }

            $violations[] = ['identifier' => $identifier, 'offsetLine' => $offsetLine, 'kind' => 'psalm'];
        }

        return $violations;
    }

    /**
     * Matches PHPStan-ignore annotations with an optional parenthesised reason.
     *
     * The directive must sit at the start of a docblock line (optional slash-star
     * or star line leader), which prevents false positives when the string appears
     * inside free-form prose. Identifiers follow the dotted camelCase convention
     * from the PHPStan error catalogue; reasons are captured without nested parentheses.
     *
     * @return list<array{0: string, 1: string, 2: int}>
     */
    private function matchPhpstanIgnore(string $text): array
    {
        return $this->matchAll(
            '~(?:^|\n)(?:[ \t/*]*)\K@phpstan-ignore(?:-next-line|-line)?[ \t]+([\w.]+)(?:[ \t]*\(([^)]*)\))?~',
            $text,
        );
    }

    /**
     * Matches Psalm-suppress annotations with an optional "-- reason" tail.
     *
     * The directive must sit at the start of a docblock line (optional `//`, `/**`,
     * or `*` line leader). Psalm identifiers are single-word camelCase so `\w+`
     * is sufficient.
     *
     * @return list<array{0: string, 1: string, 2: int}>
     */
    private function matchPsalmSuppress(string $text): array
    {
        return $this->matchAll(
            '~(?:^|\n)(?:[ \t/*]*)\K@psalm-suppress[ \t]+(\w+)(?:[ \t]*--[ \t]*(.+?))?(?:[ \t]*\*/|\s*$)~m',
            $text,
        );
    }

    /**
     * Runs a regex and returns [identifier, reason, offsetLine] triples for each match.
     *
     * A missing reason is returned as an empty string so every consumer handles a
     * single type and never a null.
     *
     * @param non-empty-string $pattern
     * @return list<array{0: string, 1: string, 2: int}>
     */
    private function matchAll(string $pattern, string $text): array
    {
        if (preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER) === false) {
            return [];
        }

        $result = [];

        foreach ($matches as $match) {
            $identifier = $match[self::IDENTIFIER_GROUP][0];
            $reason = array_key_exists(self::REASON_GROUP, $match)
                ? $match[self::REASON_GROUP][0]
                : '';
            $offset = $match[0][1];
            $offsetLine = $offset > 0
                ? substr_count(substr($text, 0, $offset), "\n")
                : 0;
            $result[] = [$identifier, $reason, $offsetLine];
        }

        return $result;
    }

    /**
     * Returns true if the identifier may be used bare or the reason is long enough.
     */
    private function isAcceptable(string $identifier, string $reason): bool
    {
        if (in_array($identifier, $this->allowedBareIdentifiers, true)) {
            return true;
        }

        return strlen(trim($reason)) >= $this->minReasonLength;
    }
}
