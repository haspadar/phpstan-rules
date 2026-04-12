<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Haspadar\PHPStanRules\Rules\ConstantUsage\ExemptScalarCollector;
use Override;
use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Detects magic numeric and string literals used outside constant declarations.
 * Numbers and strings must be defined as named constants instead of being used
 * inline. Literals inside const declarations, PHP attributes, array keys, and
 * parameter default values are exempt. The ignore lists are configurable.
 *
 * @implements Rule<Class_>
 */
final readonly class ConstantUsageRule implements Rule
{
    private const int FLOAT_DECIMAL_PLACES = 10;

    /** @var list<int|float> */
    private array $ignoreNumbers;

    private bool $checkStrings;

    /** @var list<string> */
    private array $ignoreStrings;

    /**
     * Constructs the rule with the given options.
     *
     * @param array{
     *     ignoreNumbers?: list<int|float>,
     *     checkStrings?: bool,
     *     ignoreStrings?: list<string>
     * } $options
     */
    public function __construct(array $options = [])
    {
        $this->ignoreNumbers = $options['ignoreNumbers'] ?? [0, 1];
        $this->checkStrings = $options['checkStrings'] ?? false;
        $this->ignoreStrings = $options['ignoreStrings'] ?? [''];
    }

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param Class_ $node
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $finder = new NodeFinder();
        $exempt = ExemptScalarCollector::collect($finder, $node);
        $errors = $this->checkNumbers($finder, $node, $exempt);

        if ($this->checkStrings) {
            $errors = array_merge($errors, $this->checkStringLiterals($finder, $node, $exempt));
        }

        return $errors;
    }

    /**
     * Finds magic number literals and returns errors for each.
     *
     * @param list<Scalar\Int_|Scalar\Float_|Scalar\String_> $exempt
     * @return list<IdentifierRuleError>
     */
    private function checkNumbers(NodeFinder $finder, Class_ $node, array $exempt): array
    {
        /** @var list<Scalar\Int_|Scalar\Float_> $numbers */
        $numbers = $finder->find(
            $node,
            static fn(Node $n): bool => $n instanceof Scalar\Int_ || $n instanceof Scalar\Float_,
        );

        $errors = [];

        foreach ($numbers as $number) {
            if (in_array($number, $exempt, true)) {
                continue;
            }

            $value = $this->resolveNumericValue($finder, $node, $number);

            if (in_array($value, $this->ignoreNumbers, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Magic number %s found. Define a named constant instead.', $this->formatNumber($value)),
            )
                ->identifier('haspadar.constantUsage')
                ->line($number->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Finds magic string literals and returns errors for each.
     *
     * @param list<Scalar\Int_|Scalar\Float_|Scalar\String_> $exempt
     * @return list<IdentifierRuleError>
     */
    private function checkStringLiterals(NodeFinder $finder, Class_ $node, array $exempt): array
    {
        /** @var list<ArrayItem> $items */
        $items = $finder->findInstanceOf($node, ArrayItem::class);
        $arrayKeys = [];

        foreach ($items as $item) {
            if ($item->key instanceof Scalar\String_) {
                $arrayKeys[] = $item->key;
            }
        }

        /** @var list<Scalar\String_> $strings */
        $strings = $finder->findInstanceOf($node, Scalar\String_::class);
        $errors = [];

        foreach ($strings as $string) {
            if (in_array($string, $exempt, true) || in_array($string, $arrayKeys, true)
                || in_array($string->value, $this->ignoreStrings, true)) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Magic string "%s" found. Define a named constant instead.', $string->value),
            )
                ->identifier('haspadar.constantUsage')
                ->line($string->getStartLine())
                ->build();
        }

        return $errors;
    }

    /**
     * Resolves the effective numeric value, accounting for unary minus.
     */
    private function resolveNumericValue(
        NodeFinder $finder,
        Class_ $node,
        Scalar\Int_|Scalar\Float_ $number,
    ): int|float {
        /** @var list<UnaryMinus> $negations */
        $negations = $finder->findInstanceOf($node, UnaryMinus::class);

        foreach ($negations as $neg) {
            if ($neg->expr === $number) {
                return -$number->value;
            }
        }

        return $number->value;
    }

    /**
     * Formats a number for the error message.
     */
    private function formatNumber(int|float $value): string
    {
        if (is_float($value)) {
            return rtrim(rtrim(number_format($value, self::FLOAT_DECIMAL_PLACES, '.', ''), '0'), '.');
        }

        return (string) $value;
    }
}
