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

### Design

| Rule                              | Description                                                                        |
|-----------------------------------|------------------------------------------------------------------------------------|
| `FinalClassRule`                  | All concrete classes must be `final`                                               |
| `MutableExceptionRule`            | Exception classes must not have non-readonly properties                            |
| `ReturnCountRule`                 | Method must not have more than 1 `return` statement (default: 1)                   |
| `ProtectedMethodInFinalClassRule` | Final classes must not have `protected` methods                                    |
| `ProhibitPublicStaticMethodsRule` | Classes must not have `public static` methods                                      |
| `ConstructorInitializationRule`   | Constructor must only assign `$this->property` or call `parent::__construct()`     |

### Error-prone patterns

| Rule                          | Description                                                                   |
|-------------------------------|-------------------------------------------------------------------------------|
| `NoParameterReassignmentRule` | Method parameters must not be reassigned                                      |
| `IllegalCatchRule`            | Catching `Exception`, `Throwable`, `RuntimeException`, `Error` is forbidden   |
| `IllegalThrowsRule`           | Declaring `@throws Exception` or other broad types in PHPDoc is forbidden     |
| `InnerAssignmentRule`         | Assignment inside conditions (`if ($x = foo())`) is forbidden                 |
| `ModifiedControlVariableRule` | Loop control variable must not be modified inside the loop body               |

### Naming

| Rule                              | Default | Description                                                                |
|-----------------------------------|---------|----------------------------------------------------------------------------|
| `AbbreviationAsWordInNameRule`    | 4       | Identifier must not contain more than N consecutive capital letters         |
| `VariableNameRule`                | `^[a-z][a-zA-Z]{2,19}$` | Local variable name must match the configured pattern         |
| `ParameterNameRule`               | `^(id\|[a-z]{3,})$` | Method parameter name must match the configured pattern           |
| `CatchParameterNameRule`          | `^(e\|ex\|[a-z]{3,12})$` | Catch parameter name must match the configured pattern       |

### PHPDoc style

| Rule                          | Description                                                                             |
|-------------------------------|-----------------------------------------------------------------------------------------|
| `PhpDocPunctuationClassRule`  | PHPDoc summary of every class must end with `.`, `?`, or `!`                            |
| `PhpDocPunctuationMethodRule` | PHPDoc summary of every method must end with `.`, `?`, or `!`                           |
| `AtclauseOrderRule`           | PHPDoc tags must appear in order: `@param` → `@return` → `@throws` (configurable)       |
| `PhpDocMissingClassRule`      | Every named class must have a PHPDoc comment                                            |
| `PhpDocMissingMethodRule`     | Every public method in a class must have a PHPDoc comment (configurable)                |
| `PhpDocMissingPropertyRule`   | Every public property in a class must have a PHPDoc comment (configurable)              |
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
```

Default values match the defaults described in the rules table above. Omitting a parameter keeps the default. Diagnostic identifier for `AtclauseOrderRule`: `haspadar.atclauseOrder` (for targeted ignores, e.g. `@phpstan-ignore haspadar.atclauseOrder`).

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
