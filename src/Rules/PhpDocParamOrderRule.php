<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Rules;

use Override;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * Checks that `@param` tags appear in the same order as the parameters of the method signature.
 * Extra tags (for names absent from the signature) and missing tags are both ignored — those are
 * the concern of PhpDocMissingParamRule. Only the relative order of tags that intersect with the
 * signature is inspected: the signature names filtered by presence in the tags must equal the tag
 * names filtered by presence in the signature. Non-public methods are skipped when checkPublicOnly
 * is true (default). Methods carrying the #[Override] attribute are skipped when skipOverridden is
 * true (default) because their parameter contract is inherited from the overridden method.
 *
 * @implements Rule<ClassMethod>
 */
final readonly class PhpDocParamOrderRule implements Rule
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
     * Analyses the node and returns at most one error when the `@param` order does not match the signature.
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

        $signatureNames = $this->signatureParamNames($node);
        $tagNames = $this->checker->extractParamNames($docComment->getText());

        $expectedOrder = array_values(array_intersect($signatureNames, $tagNames));
        $actualOrder = array_values(array_intersect($tagNames, $signatureNames));

        if ($expectedOrder === $actualOrder) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    '@param order for %s() does not match the signature: expected %s, got %s.',
                    $node->name->toString(),
                    implode(', ', $expectedOrder),
                    implode(', ', $actualOrder),
                ),
            )
                ->identifier('haspadar.phpdocParamOrder')
                ->build(),
        ];
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
     * Returns parameter names (with leading `$`) from the signature in declaration order.
     *
     * @return list<string>
     */
    private function signatureParamNames(ClassMethod $node): array
    {
        $names = [];

        foreach ($node->params as $param) {
            if (!$param->var instanceof Variable || !is_string($param->var->name)) {
                continue;
            }

            $names[] = sprintf('$%s', $param->var->name);
        }

        return $names;
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
