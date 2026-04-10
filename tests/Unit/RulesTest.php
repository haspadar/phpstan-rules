<?php

declare(strict_types=1);

namespace Haspadar\PHPStanRules\Tests\Unit;

use Haspadar\PHPStanRules\Rules;
use Haspadar\PHPStanRules\Rules\BooleanExpressionComplexityRule;
use Haspadar\PHPStanRules\Rules\CouplingBetweenObjectsRule;
use Haspadar\PHPStanRules\Rules\CognitiveComplexityRule;
use Haspadar\PHPStanRules\Rules\CyclomaticComplexityRule;
use Haspadar\PHPStanRules\Rules\ClassLengthRule;
use Haspadar\PHPStanRules\Rules\FileLengthRule;
use Haspadar\PHPStanRules\Rules\FinalClassRule;
use Haspadar\PHPStanRules\Rules\MethodLengthRule;
use Haspadar\PHPStanRules\Rules\ParameterNumberRule;
use Haspadar\PHPStanRules\Rules\MutableExceptionRule;
use Haspadar\PHPStanRules\Rules\ProtectedMethodInFinalClassRule;
use Haspadar\PHPStanRules\Rules\ConstructorInitializationRule;
use Haspadar\PHPStanRules\Rules\IllegalCatchRule;
use Haspadar\PHPStanRules\Rules\IllegalThrowsRule;
use Haspadar\PHPStanRules\Rules\InnerAssignmentRule;
use Haspadar\PHPStanRules\Rules\ModifiedControlVariableRule;
use Haspadar\PHPStanRules\Rules\NoParameterReassignmentRule;
use Haspadar\PHPStanRules\Rules\PhpDocPunctuationClassRule;
use Haspadar\PHPStanRules\Rules\PhpDocPunctuationMethodRule;
use Haspadar\PHPStanRules\Rules\PhpDocEmptyClassRule;
use Haspadar\PHPStanRules\Rules\AtclauseOrderRule;
use Haspadar\PHPStanRules\Rules\PhpDocEmptyMethodRule;
use Haspadar\PHPStanRules\Rules\PhpDocMissingClassRule;
use Haspadar\PHPStanRules\Rules\PhpDocMissingMethodRule;
use Haspadar\PHPStanRules\Rules\PhpDocMissingPropertyRule;
use Haspadar\PHPStanRules\Rules\ClassConstantTypeHintRule;
use Haspadar\PHPStanRules\Rules\AbbreviationAsWordInNameRule;
use Haspadar\PHPStanRules\Rules\NoInlineCommentRule;
use Haspadar\PHPStanRules\Rules\CatchParameterNameRule;
use Haspadar\PHPStanRules\Rules\ParameterNameRule;
use Haspadar\PHPStanRules\Rules\VariableNameRule;
use Haspadar\PHPStanRules\Rules\NoLineCommentBeforeDeclarationRule;
use Haspadar\PHPStanRules\Rules\NoPhpDocForOverriddenRule;
use Haspadar\PHPStanRules\Rules\ParamDescriptionCapitalRule;
use Haspadar\PHPStanRules\Rules\ReturnDescriptionCapitalRule;
use Haspadar\PHPStanRules\Rules\ProhibitPublicStaticMethodsRule;
use Haspadar\PHPStanRules\Rules\ReturnCountRule;
use Haspadar\PHPStanRules\Rules\StatementCountRule;
use Haspadar\PHPStanRules\Rules\TooManyMethodsRule;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RulesTest extends TestCase
{
    #[Test]
    public function returnsAllRegisteredRules(): void
    {
        self::assertSame(
            [
                MethodLengthRule::class,
                FileLengthRule::class,
                ClassLengthRule::class,
                TooManyMethodsRule::class,
                ParameterNumberRule::class,
                CyclomaticComplexityRule::class,
                CognitiveComplexityRule::class,
                CouplingBetweenObjectsRule::class,
                BooleanExpressionComplexityRule::class,
                StatementCountRule::class,
                FinalClassRule::class,
                MutableExceptionRule::class,
                ReturnCountRule::class,
                ProtectedMethodInFinalClassRule::class,
                ProhibitPublicStaticMethodsRule::class,
                ConstructorInitializationRule::class,
                NoParameterReassignmentRule::class,
                IllegalCatchRule::class,
                IllegalThrowsRule::class,
                InnerAssignmentRule::class,
                ModifiedControlVariableRule::class,
                PhpDocPunctuationClassRule::class,
                PhpDocPunctuationMethodRule::class,
                PhpDocEmptyClassRule::class,
                PhpDocEmptyMethodRule::class,
                AtclauseOrderRule::class,
                PhpDocMissingClassRule::class,
                PhpDocMissingMethodRule::class,
                PhpDocMissingPropertyRule::class,
                ReturnDescriptionCapitalRule::class,
                ParamDescriptionCapitalRule::class,
                NoPhpDocForOverriddenRule::class,
                ClassConstantTypeHintRule::class,
                NoLineCommentBeforeDeclarationRule::class,
                NoInlineCommentRule::class,
                AbbreviationAsWordInNameRule::class,
                VariableNameRule::class,
                ParameterNameRule::class,
                CatchParameterNameRule::class,
            ],
            (new Rules())->all(),
            'Rules::all() must list every registered rule class',
        );
    }
}
