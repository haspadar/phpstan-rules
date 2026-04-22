<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that PHPDoc tags in class methods appear in the required order.
 * Only the relative order of tags present in the doc block is enforced.
 * Tags not listed in tagOrder are ignored. Methods without a PHPDoc block,
 * methods in interfaces and traits, and blocks with no relevant tags are skipped.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class AtclauseOrderRule implements Rule
{
    private const int MINIMUM_TAGS_TO_CHECK = 2;

    private const int NOT_FOUND = -1;

    /** @var list<string> */
    private array $tagOrder;

    /**
     * Constructs the rule with the given tag order configuration.
     *
     * @param array{tagOrder?: list<string>} $options Expected order of PHPDoc tag names.
     */
    public function __construct(array $options = [])
    {
        $tags = $options['tagOrder'] ?? ['@param', '@return', '@throws'];
        $this->tagOrder = array_map(
            static fn(string $tag): string => str_starts_with($tag, '@') ? $tag : sprintf('@%s', $tag),
            $tags,
        );
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $scope->getClassReflection();

        $docComment = $node->getDocComment();

        if ($reflection === null || !$reflection->isClass() || $docComment === null) {
            return [];
        }

        $relevant = $this->relevantTags($docComment->getText());

        if (count($relevant) < self::MINIMUM_TAGS_TO_CHECK) {
            return [];
        }

        return $this->detectViolations($relevant, $node->name->toString());
    }

    /**
     * Filters PHPDoc tags to those listed in tagOrder, preserving document order.
     *
     * @return list<string>
     */
    private function relevantTags(string $docText): array
    {
        preg_match_all('/^\s*\*\s*(@\w+)/m', $docText, $matches);
        $found = $matches[1];

        return array_values(array_filter($found, fn(string $tag) => in_array($tag, $this->tagOrder, true)));
    }

    /**
     * Returns errors for any tags appearing out of required order.
     *
     * @param list<string> $tags
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function detectViolations(array $tags, string $methodName): array
    {
        $errors = [];
        $lastIndex = self::NOT_FOUND;
        $lastTag = '';

        foreach ($tags as $tag) {
            $index = array_search($tag, $this->tagOrder, true);

            if ($index !== false && $index < $lastIndex) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'PHPDoc tag %s must come before %s in %s().',
                        $tag,
                        $lastTag,
                        $methodName,
                    ),
                )
                    ->identifier('haspadar.atclauseOrder')
                    ->build();
            }

            if ($index !== false && $index >= $lastIndex) {
                $lastIndex = $index;
                $lastTag = $tag;
            }
        }

        return $errors;
    }
}
