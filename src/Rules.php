<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules;

/**
 * Entry point for PHPStan rules registration.
 */
final class Rules
{
    private const array ALL = [
        Rules\MethodLengthRule::class,
        Rules\FileLengthRule::class,
        Rules\ClassLengthRule::class,
        Rules\TooManyMethodsRule::class,
        Rules\ParameterNumberRule::class,
        Rules\CyclomaticComplexityRule::class,
        Rules\CognitiveComplexityRule::class,
        Rules\CouplingBetweenObjectsRule::class,
        Rules\BooleanExpressionComplexityRule::class,
        Rules\StatementCountRule::class,
        Rules\FinalClassRule::class,
        Rules\MutableExceptionRule::class,
        Rules\ReturnCountRule::class,
        Rules\ProtectedMethodInFinalClassRule::class,
        Rules\ProhibitStaticMethodsRule::class,
        Rules\ProhibitStaticPropertiesRule::class,
        Rules\ConstructorInitializationRule::class,
        Rules\NoParameterReassignmentRule::class,
        Rules\IllegalCatchRule::class,
        Rules\IllegalThrowsRule::class,
        Rules\InnerAssignmentRule::class,
        Rules\ModifiedControlVariableRule::class,
        Rules\PhpDocPunctuationClassRule::class,
        Rules\PhpDocPunctuationMethodRule::class,
        Rules\PhpDocEmptyClassRule::class,
        Rules\PhpDocEmptyMethodRule::class,
        Rules\AtclauseOrderRule::class,
        Rules\PhpDocMissingClassRule::class,
        Rules\PhpDocMissingMethodRule::class,
        Rules\PhpDocMissingPropertyRule::class,
        Rules\PhpDocMissingParamRule::class,
        Rules\PhpDocParamDescriptionRule::class,
        Rules\PhpDocParamOrderRule::class,
        Rules\ReturnDescriptionCapitalRule::class,
        Rules\ParamDescriptionCapitalRule::class,
        Rules\NoPhpDocForOverriddenRule::class,
        Rules\ClassConstantTypeHintRule::class,
        Rules\NoLineCommentBeforeDeclarationRule::class,
        Rules\NoInlineCommentRule::class,
        Rules\AbbreviationAsWordInNameRule::class,
        Rules\VariableNameRule::class,
        Rules\ParameterNameRule::class,
        Rules\CatchParameterNameRule::class,
        Rules\UnnecessaryLocalRule::class,
        Rules\ConstantUsageRule::class,
        Rules\StringLiteralsConcatenationRule::class,
        Rules\TodoCommentRule::class,
        Rules\ForbiddenClassSuffixRule::class,
        Rules\BeImmutableRule::class,
        Rules\KeepInterfacesShortRule::class,
        Rules\NeverAcceptNullArgumentsRule::class,
        Rules\NeverReturnNullRule::class,
        Rules\NoNullAssignmentRule::class,
        Rules\NoNullablePropertyRule::class,
        Rules\NoNullArgumentRule::class,
        Rules\NeverUsePublicConstantsRule::class,
        Rules\WeightedMethodsPerClassRule::class,
        Rules\AfferentCouplingRule::class,
        Rules\InheritanceDepthRule::class,
        Rules\LackOfCohesionRule::class,
        Rules\InstabilityRule::class,
        Rules\NoActorSuffixRule::class,
    ];

    /**
     * Returns the list of rule class names provided by this extension.
     *
     * @return list<class-string>
     */
    public function all(): array
    {
        return self::ALL;
    }
}
