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
    /** @var list<string> */
    private array $tagOrder;

    /**
     * @param array{tagOrder?: list<string>} $options
     */
    public function __construct(array $options = [])
    {
        $this->tagOrder = $options['tagOrder'] ?? ['@param', '@return', '@throws'];
    }

    /** @psalm-suppress InvalidAttribute -- psalm/psalm#11723 */
    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @psalm-suppress InvalidAttribute -- psalm/psalm#11723
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $scope->getClassReflection();

        /** @var ClassMethod $node */
        $docComment = $node->getDocComment();

        if ($reflection === null || !$reflection->isClass() || $docComment === null) {
            return [];
        }

        $relevant = $this->relevantTags($docComment->getText());

        if (count($relevant) < 2) {
            return [];
        }

        return $this->detectViolations($relevant, $node->name->toString());
    }

    /**
     * Filters PHPDoc tags to those listed in tagOrder, preserving document order
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
     * Returns errors for any tags appearing out of required order
     *
     * @param list<string> $tags
     *
     * @throws \PHPStan\ShouldNotHappenException
     *
     * @return list<IdentifierRuleError>
     */
    private function detectViolations(array $tags, string $methodName): array
    {
        $errors = [];
        $lastIndex = -1;
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
