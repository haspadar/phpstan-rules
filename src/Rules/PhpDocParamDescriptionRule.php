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
 * Checks that every `@param` tag in a method PHPDoc block has a non-empty description.
 * The rule does not require `@param` tags to be present — that is the job of
 * PhpDocMissingParamRule. When a tag is present, the text after the parameter name must
 * carry meaning; empty descriptions reduce a tag to a duplicate of the native signature
 * and leave the reader without the semantic information the tag is meant to provide.
 * Non-public methods are skipped when checkPublicOnly is true (default). Methods with the
 * #[Override] attribute are skipped when skipOverridden is true (default) because the
 * description is inherited from the overridden method.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class PhpDocParamDescriptionRule implements Rule
{
    private bool $checkPublicOnly;

    private bool $skipOverridden;

    private PhpDocDescriptionChecker $checker;

    /**
     * Constructs the rule with visibility and override options, initialising the shared PHPDoc checker.
     *
     * @param array{checkPublicOnly?: bool, skipOverridden?: bool} $options Visibility filter and `#[Override]` skip flag.
     * @throws ShouldNotHappenException
     */
    public function __construct(array $options = [])
    {
        $this->checkPublicOnly = $options['checkPublicOnly'] ?? true;
        $this->skipOverridden = $options['skipOverridden'] ?? true;
        $this->checker = new PhpDocDescriptionChecker();
    }

    #[Override]
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * Analyses the node and returns a list of errors, one per `@param` tag without a description.
     *
     * @psalm-param ClassMethod $node
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $docComment = $node->getDocComment();

        if ($this->shouldSkip($node, $scope) || $docComment === null) {
            return [];
        }

        $docText = $docComment->getText();
        $allTags = $this->checker->extractParamNames($docText);
        $withDescription = array_keys($this->checker->extractParamDescriptions($docText));

        return $this->collectEmpty(array_values(array_diff($allTags, $withDescription)), $node->name->toString());
    }

    /**
     * Returns true when the rule must not inspect the given method (wrong scope, filtered by options, etc).
     */
    private function shouldSkip(ClassMethod $node, Scope $scope): bool
    {
        $reflection = $scope->getClassReflection();

        return $reflection === null
            || !$reflection->isClass()
            || ($this->checkPublicOnly && !$node->isPublic())
            || ($this->skipOverridden && $this->hasOverrideAttribute($node));
    }

    /**
     * Builds one error per `@param` tag that has an empty description.
     *
     * @param list<string> $emptyTags Parameter names (with leading `$`) whose description is empty.
     * @param string $methodName Method name used in the error message.
     * @throws ShouldNotHappenException
     * @return list<IdentifierRuleError>
     */
    private function collectEmpty(array $emptyTags, string $methodName): array
    {
        $errors = [];

        foreach ($emptyTags as $paramName) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    '@param %s for %s() is missing a description.',
                    $paramName,
                    $methodName,
                ),
            )
                ->identifier('haspadar.phpdocParamDescription')
                ->build();
        }

        return $errors;
    }

    /**
     * Returns true if the method has the #[Override] attribute (with or without leading backslash).
     */
    private function hasOverrideAttribute(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if (in_array($attr->name->toString(), ['Override', '\Override'], true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
