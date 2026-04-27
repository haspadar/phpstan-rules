# Opinionated PHPStan Rules

[![CI](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml/badge.svg)](https://github.com/haspadar/phpstan-rules/actions/workflows/piqule.yml)
[![Coverage](https://codecov.io/gh/haspadar/phpstan-rules/branch/main/graph/badge.svg)](https://codecov.io/gh/haspadar/phpstan-rules)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fhaspadar%2Fphpstan-rules%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/haspadar/phpstan-rules/main)
[![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/haspadar/phpstan-rules?labelColor=171717&color=FF570A&label=CodeRabbit+Reviews)](https://coderabbit.ai)

---

## Rules

### Metrics

| Rule                              | Default | Description                                                                |
|-----------------------------------|---------|----------------------------------------------------------------------------|
| `MethodLengthRule`                | 100     | Method body must not exceed N lines                                        |
| `FileLengthRule`                  | 1000    | File must not exceed N lines                                               |
| `TooManyMethodsRule`              | 20      | Class must not have more than N methods                                    |
| `ParameterNumberRule`             | 3       | Method must not have more than N parameters                                |
| `CyclomaticComplexityRule`        | 10      | Method cyclomatic complexity must not exceed N                             |
| `CognitiveComplexityRule`         | 10      | Method cognitive complexity must not exceed N (nesting is penalised)       |
| `CouplingBetweenObjectsRule`      | 15      | Class must not depend on more than N unique types                          |
| `BooleanExpressionComplexityRule` | 3       | Method must not have more than N boolean operators in a single expression  |
| `ClassLengthRule`                 | 500     | Class body must not exceed N lines                                         |
| `StatementCountRule`              | 30      | Method must not have more than N executable statements                     |
| `WeightedMethodsPerClassRule`     | 50      | Sum of cyclomatic complexities of all methods must not exceed N            |
| `AfferentCouplingRule`            | 14      | Class must not be referenced by more than N other classes in the codebase  |
| `InheritanceDepthRule`            | 3       | Class must not extend a chain of more than N ancestors                     |
| `LackOfCohesionRule`              | 1       | Class methods must not split into more than N disjoint LCOM4 groups        |

### Design

| Rule                              | Description                                                                        |
|-----------------------------------|------------------------------------------------------------------------------------|
| `FinalClassRule`                  | All concrete classes must be `final`                                               |
| `MutableExceptionRule`            | Exception classes must not have non-readonly properties                            |
| `ReturnCountRule`                 | Method must not have more than 1 `return` statement (default: 1)                   |
| `ProtectedMethodInFinalClassRule` | Final classes must not have `protected` methods                                    |
| `ProhibitStaticMethodsRule`       | Classes must not declare `static` methods of any visibility                        |
| `ProhibitStaticPropertiesRule`    | Classes must not declare `static` properties of any visibility                     |
| `ConstructorInitializationRule`   | Constructor must only assign `$this->property` or call `parent::__construct()`     |
| `BeImmutableRule`                | All non-static properties must be `readonly`                                       |
| `KeepInterfacesShortRule`        | Interfaces must not declare too many methods (default: 10)                         |
| `NeverAcceptNullArgumentsRule`   | Method and standalone function parameters must not be nullable                     |
| `NeverReturnNullRule`            | Method and standalone function return types must not be nullable, `return null` is forbidden |
| `NoNullAssignmentRule`           | Plain assignments of the `null` literal (variable, property, array element) are forbidden |
| `NoNullablePropertyRule`         | Class property types must not be nullable (`?Type`, `Type\|null`, `null\|Type`, `null`) |
| `NoNullArgumentRule`             | Passing the `null` literal to user-defined functions, methods (including static and nullsafe), or constructors is forbidden |
| `NeverUsePublicConstantsRule`    | Class constants must not be public (explicitly or implicitly)                      |

### Error-prone patterns

| Rule                          | Description                                                                   |
|-------------------------------|-------------------------------------------------------------------------------|
| `NoParameterReassignmentRule` | Method parameters must not be reassigned                                      |
| `IllegalCatchRule`            | Catching `Exception`, `Throwable`, `RuntimeException`, `Error` is forbidden   |
| `IllegalThrowsRule`           | Declaring `@throws Exception` or other broad types in PHPDoc is forbidden     |
| `InnerAssignmentRule`         | Assignment inside conditions (`if ($x = foo())`) is forbidden                 |
| `ModifiedControlVariableRule` | Loop control variable must not be modified inside the loop body               |
| `UnnecessaryLocalRule`        | Local variable assigned and immediately returned/thrown must be inlined        |
| `ConstantUsageRule`           | Magic numbers and strings must be defined as named constants                  |
| `StringLiteralsConcatenationRule` | String literal concatenation via `.` or `.=` is forbidden                |
| `TodoCommentRule`             | TODO, FIXME, and XXX comments are forbidden in method bodies                  |
| `MissingThrowsRule`           | Methods must declare `@throws` for every checked exception they throw (overridden methods inherit by default) |
| `HiddenFieldRule`             | Method parameter or local variable must not shadow a class property (promoted constructors excluded, parameter takes precedence over local of the same name) |
| `RequireIgnoreReasonRule`     | Every `@phpstan-ignore` and `@psalm-suppress` must carry a justification (default: 5 chars, parens for PHPStan, `--` for Psalm) |
| `MultipleVariableDeclarationsRule` | Chained assignments (`$a = $b = 1`) and multiple statements on one line are forbidden (default: chained `null` chains rejected) |
| `NestedIfDepthRule`           | Nested `if` depth must not exceed the configured limit (default: 1; `elseif`/`else` and `Closure` reset depth) |
| `NestedForDepthRule`          | Nested loop depth (`for`/`foreach`/`while`/`do-while`) must not exceed the configured limit (default: 1; `Closure` and arrow functions reset depth) |
| `NestedTryDepthRule`          | Nested `try` depth must not exceed the configured limit (default: 1; `catch`/`finally` and `Closure` and arrow functions reset depth) |

### Naming

| Rule                              | Default | Description                                                                |
|-----------------------------------|---------|----------------------------------------------------------------------------|
| `AbbreviationAsWordInNameRule`    | 4       | Identifier must not contain more than N consecutive capital letters         |
| `VariableNameRule`                | `^[a-z][a-zA-Z]{2,19}$` | Local variable name must match the configured pattern         |
| `ParameterNameRule`               | `^(id\|[a-z]{3,})$` | Method parameter name must match the configured pattern           |
| `CatchParameterNameRule`          | `^(e\|ex\|[a-z]{3,12})$` | Catch parameter name must match the configured pattern       |
| `ForbiddenClassSuffixRule`        | 12 suffixes | Class name must not end with a generic suffix (Manager, Helper, Util, ...) |
| `NoActorSuffixRule`               | 27 words, 6 ns prefixes | Class ending with -er/-or must match the allowedWords whitelist, or extend a class from a framework namespace |

### PHPDoc style

| Rule                          | Description                                                                             |
|-------------------------------|-----------------------------------------------------------------------------------------|
| `PhpDocPunctuationClassRule`  | PHPDoc summary of every class must end with `.`, `?`, or `!`                            |
| `PhpDocPunctuationMethodRule` | PHPDoc summary of every method must end with `.`, `?`, or `!`                           |
| `AtclauseOrderRule`           | PHPDoc tags must appear in order: `@param` → `@return` → `@throws` (configurable)       |
| `PhpDocMissingClassRule`      | Every named class must have a PHPDoc comment                                            |
| `PhpDocMissingMethodRule`     | Every public method in a class must have a PHPDoc comment (configurable)                |
| `PhpDocMissingPropertyRule`   | Every public property in a class must have a PHPDoc comment (configurable)              |
| `PhpDocMissingParamRule`      | Every parameter of a method with a PHPDoc block must have a matching `@param` tag       |
| `PhpDocParamDescriptionRule`  | Every `@param` tag must have a non-empty description after the parameter name           |
| `PhpDocParamOrderRule`        | `@param` tags must appear in the same order as the parameters of the method signature   |
| `ReturnDescriptionCapitalRule` | `@return` tag description must start with a capital letter                             |
| `ParamDescriptionCapitalRule`  | `@param` tag descriptions must start with a capital letter                             |
| `NoPhpDocForOverriddenRule`    | Overridden methods (`#[Override]`) must not have a PHPDoc comment                      |
| `ClassConstantTypeHintRule`    | Every class constant must have a native type declaration (PHP 8.3+)                    |
| `NoLineCommentBeforeDeclarationRule` | `//` and `#` comments are forbidden before class, method, and property declarations |
| `NoInlineCommentRule`          | Comments inside method bodies are forbidden (suppress directives with `@` are allowed) |

---

### Configuration

All configurable rules expose their options as PHPStan parameters under the `haspadar` namespace. Override any limit in your `phpstan.neon` without touching service definitions:

```neon
parameters:
    haspadar:
        methodLength:
            maxLines: 50
            skipBlankLines: true
            skipComments: true
        fileLength:
            maxLines: 500
        tooManyMethods:
            maxMethods: 10
            onlyPublic: true
        parameterNumber:
            maxParameters: 5
            ignoreOverridden: false
        cyclomaticComplexity:
            maxComplexity: 5
        couplingBetweenObjects:
            maximum: 10
            excludedClasses:
                - Symfony\Component\HttpFoundation\Request
        booleanExpressionComplexity:
            maxOperators: 2
        classLength:
            maxLines: 250
            skipBlankLines: true
            skipComments: true
        statementCount:
            maxStatements: 20
        weightedMethods:
            maxWmc: 30
        returnCount:
            max: 2
        illegalCatch:
            illegalClassNames:
                - Exception
                - Throwable
        illegalThrows:
            illegalClassNames:
                - Error
                - Throwable
            ignoreOverriddenMethods: false
        phpDocPunctuationClass:
            checkCapitalization: false
        phpDocPunctuationMethod:
            checkCapitalization: false
        atclauseOrder:
            tagOrder:
                - '@param'
                - '@return'
                - '@throws'
        phpDocMissingMethod:
            checkPublicOnly: true
            skipOverridden: true
        phpDocMissingProperty:
            checkPublicOnly: true
        phpDocMissingParam:
            checkPublicOnly: true
            skipOverridden: true
        phpDocParamDescription:
            checkPublicOnly: true
            skipOverridden: true
        phpdocParamOrder:
            checkPublicOnly: true
            skipOverridden: true
        abbreviation:
            maxAllowedConsecutiveCapitals: 3
            allowedAbbreviations:
                - JSON
                - HTTP
        variableName:
            pattern: '^[a-z][a-zA-Z]{2,9}$'
            allowedNames:
                - id
                - i
                - j
                - db
        parameterName:
            pattern: '^(id|[a-z]{3,})$'
        catchParamName:
            pattern: '^(e|ex|[a-z]{3,12})$'
        constantUsage:
            ignoreNumbers:
                - 0
                - 1
            checkStrings: false
            ignoreStrings:
                - ''
        stringConcat:
            allowMixed: false
        todoComment:
            keywords:
                - TODO
                - FIXME
                - XXX
        beImmutable:
            excludedClasses:
                - App\Entity\User
                - App\Entity\Order
        interfaceMethods:
            maxMethods: 5
        forbiddenClassSuffix:
            forbiddenSuffixes:
                - Manager
                - Handler
                - Processor
                - Coordinator
                - Helper
                - Util
                - Utils
                - Utility
                - Data
                - Info
                - Information
                - Wrapper
            allowedSuffixes:
                - EventHandler
                - CommandHandler
        noActorSuffix:
            allowedWords:
                - User
                - Order
                - Number
                - Member
                - Owner
                - Customer
                - Folder
                - Header
                - Footer
                - Buffer
                - Layer
                - Marker
                - Parameter
                - Character
                - Identifier
                - Integer
                - Author
                - Visitor
                - Error
                - Color
                - Vendor
                - Vector
                - Factor
                - Actor
                - Director
                - Ancestor
                - Descriptor
            excludedParentNamespaces:
                - 'Symfony\'
                - 'Illuminate\'
                - 'Doctrine\'
                - 'Laminas\'
                - 'Yii\'
                - 'Laravel\'
            excludedClasses:
                - App\Legacy\UserManager
        missingThrows:
            skipOverridden: true
        hiddenField:
            ignoreConstructorParameter: true
            ignoreAbstractMethods: false
            ignoreSetter: false
            ignoreNames: []
        requireIgnoreReason:
            minReasonLength: 5
            allowedBareIdentifiers: []
        multipleVarDecl:
            allowChainedNull: false
        nestedIfDepth:
            maxDepth: 1
        nestedForDepth:
            maxDepth: 1
        nestedTryDepth:
            maxDepth: 1
        afferentCoupling:
            maxAfferent: 10
            ignoreInterfaces: true
            ignoreAbstract: true
            excludedClasses:
                - App\Kernel
        inheritanceDepth:
            maxDepth: 2
            excludedClasses:
                - Symfony\Bundle\FrameworkBundle\Controller\AbstractController
        lackOfCohesion:
            maxLcom: 1
            minMethods: 7
            minProperties: 3
            excludedClasses:
                - App\Entity\User
```

Default values match the defaults described in the rules table above. Omitting a parameter keeps the default. Diagnostic identifier for `AtclauseOrderRule`: `haspadar.atclauseOrder` (for targeted ignores, e.g. `@phpstan-ignore haspadar.atclauseOrder`).

### NoActorSuffixRule — allowedWords vs renaming

When the rule reports a class like `UserDispatcher`, pick one of three fixes:

1. **Rename the class to a domain noun (preferred).** `UserDispatcher` becomes `User`, `UserEvent`, `UserNotification` — whatever the class actually *is*, not what it does.
2. **Extend `allowedWords` if the suffix is a real English noun describing an entity**, not an action. Good candidates: `Container`, `Editor`, `Monitor`, `Sensor`. Bad candidates (these are actors, not entities): `Manager`, `Controller`, `Handler`, `Dispatcher`, `Coordinator`, `Orchestrator`, `Processor`.
3. **Add a framework namespace to `excludedParentNamespaces` if the class is framework-managed** (extends a controller base, implements an event-subscriber interface, etc.). Do not put `Controller` or `Handler` into `allowedWords` for this — it defeats the rule.

Rule of thumb: if the suffix describes *what the class is*, extend `allowedWords`. If it describes *what the class does*, rename.

`allowedWords` is matched **case-sensitively** against the last PascalCase segment of the class name. PHP class names follow PascalCase convention, so entries must be capitalized (`User`, not `user`).

### RequireIgnoreReasonRule — where to put the reason

Two different delimiters, one per tool:

```php
/** @phpstan-ignore foo.bar (reason in parentheses — PHPStan 1.11+ native) */
/** @psalm-suppress FooBar -- reason after double-dash (ESLint convention) */
```

`minReasonLength` counts **trimmed** characters, so padding does not help. `allowedBareIdentifiers` skips both the reason requirement and length check — use it for self-evident project-wide suppressions.

### MissingThrowsRule — @throws inheritance for overridden methods

This rule replaces PHPStan's built-in `exceptions.check.missingCheckedExceptionInThrows` for class methods so that overrides and interface implementations do not have to repeat `@throws` from the parent contract.

Including `rules.neon` from this package automatically sets `exceptions.check.missingCheckedExceptionInThrows: false` — the built-in check is turned off and replaced by `haspadar.missingThrows`. Do **not** re-enable the built-in flag in your own `phpstan.neon`: both rules will then fire on the same code and you will receive duplicate errors.

Current scope: only class methods are covered. Standalone functions and PHP 8.4 property hooks are not yet checked by `haspadar.missingThrows`; if your codebase needs `@throws` enforcement there, keep those analyses through separate means until the corresponding rules are shipped.

- `skipOverridden: true` (default) — overridden/interface-implementing methods inherit `@throws` from the parent and are not required to declare it themselves.
- `skipOverridden: false` — every method must declare `@throws` for every checked exception it throws, including overrides.

---

## Experimental rules

Some rules are not registered by default because their usefulness depends strongly on project topology. They live behind an opt-in include so adopting projects do not fail on legitimate code (for example, entry-point classes that naturally have instability `I = 1`).

To enable them, add `rules-experimental.neon` to your `phpstan.neon`:

```neon
includes:
    - vendor/haspadar/phpstan-rules/rules.neon
    - vendor/haspadar/phpstan-rules/rules-experimental.neon
```

| Rule              | Why opt-in                                                                       |
|-------------------|-----------------------------------------------------------------------------------|
| `InstabilityRule` | Absolute threshold on a relative metric; `I = 1` is normal for entry-point classes |

Once enabled, configure the rule like any other:

```neon
parameters:
    haspadar:
        instability:
            maxInstability: 0.8
            minDependencies: 5
            ignoreInterfaces: true
            ignoreAbstract: true
            excludedClasses:
                - App\Controller\HomeController
```

---

## Installation

```bash
composer require --dev haspadar/phpstan-rules
```

---

## Contributing

Fork the repository, apply changes, and open a pull request.

---

## License

MIT
