# Qulice vs PHP — Вердикт

Источник: [qulice-checks.md](qulice-checks.md)

Инструменты Piqule: PHPStan (level 9), Psalm (level 1), PHP-CS-Fixer, PHPCS, PHPUnit, Infection, Typos.

> **PHPMD, PHPMetrics, PHPLOC исключены:** заброшены, не поддерживают PHP 8.3+.
> **Архитектурное решение:** один пакет — PHPStan extension. PHPCS не нужен: `phpstan/phpdoc-parser` (встроен в PHPStan) сохраняет текст `@return`/`@param` description, порядок тегов и summary — все «текстовые» PHPDoc-проверки реализуемы в PHPStan rule. Метрики строк — через `getStartLine()`/`getEndLine()` в AST.

---

## 1. Checkstyle — уровень файла

### Checker

| Правило                              | Вердикт                                                    | Где реализовать (если нет)     |
|--------------------------------------|------------------------------------------------------------|--------------------------------|
| `JavadocPackage`                     | Неактуально — в PHP нет package-level PHPDoc               | —                              |
| `NewlineAtEndOfFile`                 | Есть — **PHP-CS-Fixer** (`final_newline`)                  | —                              |
| `Translation`                        | Неактуально — механизм Checkstyle для `.properties`-файлов | —                              |
| `FileLength` (max 1000)              | Нет готового решения — PHPLOC заархивирован                | Кастомный PHPStan rule         |
| `FileTabCharacter`                   | Есть — **PHP-CS-Fixer** (запрет табов, `indentation_type`) | —                              |
| `LineLength` (max 100)               | Есть — **PHP-CS-Fixer** + **PHPCS**                        | —                              |
| `SuppressWithPlainTextCommentFilter` | Неактуально — Java-специфичный механизм подавления         | —                              |

### RegexpSingleline

| id                                               | Вердикт                                                                      | Где реализовать (если нет)          |
|--------------------------------------------------|------------------------------------------------------------------------------|-------------------------------------|
| `TrailingLineSpace`                              | Есть — **PHP-CS-Fixer** (`no_trailing_whitespace`)                           | —                                   |
| `ReturnShouldStartWithCapitalLetter`             | Нет готового решения — стиль PHPDoc не стандартизован на уровне инструментов | Кастомный PHPCS sniff               |
| `ParamShouldStartWithCapitalLetter`              | Нет готового решения — аналогично                                            | Кастомный PHPCS sniff               |
| `UsingThisAsLock`, `UsingClassAsLock`            | Неактуально — в PHP нет `synchronized`                                       | —                                   |
| `UseStandardCharsets`, `UseStandardJavaCharsets` | Неактуально — Java-специфичная API                                           | —                                   |
| `EndOfLineSymbolLimits`                          | Нет готового решения                                                         | Кастомный PHPCS sniff               |
| `StartLineSymbolLimits`                          | Нет готового решения                                                         | Кастомный PHPCS sniff               |

### RegexpMultiline

| id                              | Вердикт                                          | Где реализовать (если нет) |
|---------------------------------|--------------------------------------------------|----------------------------|
| `UnixEndOfLine`                 | Есть — **PHP-CS-Fixer** (`line_ending`)          | —                          |
| `TwoConsecutiveEmptyLines`      | Есть — **PHP-CS-Fixer** (`no_extra_blank_lines`) | —                          |
| `ArrayListShouldBeInitWithSize` | Неактуально — в PHP массивы динамические         | —                          |

---

## 2. Checkstyle TreeWalker

### Аннотации

| Правило              | Вердикт                                                                                              | Где реализовать (если нет)                |
|----------------------|------------------------------------------------------------------------------------------------------|-------------------------------------------|
| `AnnotationUseStyle` | Нет готового решения — стиль PHP-атрибутов (`#[...]`) не проверяется                                 | Кастомный PHPCS sniff                     |
| `MissingDeprecated`  | Нет готового решения — PHPStan/Psalm проверяют использование `@deprecated`, но не требуют его        | Кастомный PHPStan rule                    |
| `MissingOverride`    | Неактуально — в PHP нет обязательного `@Override`; `#[Override]` с PHP 8.3 проверяется PHPStan/Psalm | —                                         |
| `PackageAnnotation`  | Неактуально — Java-специфично                                                                        | —                                         |
| `SuppressWarnings`   | Неактуально — Java-специфично                                                                        | —                                         |

### Блоки

| Правило                                 | Вердикт                                                                | Где реализовать (если нет)        |
|-----------------------------------------|------------------------------------------------------------------------|-----------------------------------|
| `EmptyBlock`                            | Есть — **PHPStan** + **PHP-CS-Fixer** (`no_empty_statement`)           | —                                 |
| `LeftCurly`, `RightCurly`, `NeedBraces` | Есть — **PHP-CS-Fixer** (`control_structure_braces`)                   | —                                 |
| `AvoidNestedBlocks`                     | Нет готового решения — частично через **PHPMD** `CyclomaticComplexity` | Кастомный PHPCS sniff             |

### Дизайн классов

| Правило                       | Вердикт                                                                            | Где реализовать (если нет)                                            |
|-------------------------------|------------------------------------------------------------------------------------|-----------------------------------------------------------------------|
| `VisibilityModifier`          | Есть — **PHPCS** + **PHP-CS-Fixer** (`visibility_required`)                        | —                                                                     |
| `FinalClass`                  | Частично — **ergebnis/phpstan-rules** `FinalRule`, **Psalm** `ClassMustBeFinal`    | Уже есть: `ergebnis/phpstan-rules` или Psalm issue type               |
| `InterfaceIsType`             | Неактуально — запрет constant-only интерфейсов; нет PHP-инструмента                | —                                                                     |
| `HideUtilityClassConstructor` | Нет готового решения                                                               | Кастомный PHPStan rule                                                |
| `DesignForExtension`          | Неактуально — Elegant Objects запрещает наследование вообще                        | —                                                                     |
| `MutableException`            | Нет готового решения                                                               | Кастомный PHPStan rule                                                |
| `ThrowsCount`                 | Нет готового решения — PHPStan отслеживает исключения, но не лимитирует количество | Кастомный PHPStan rule                                                |
| `InnerTypeLast`               | Неактуально — в PHP нет вложенных классов                                          | —                                                                     |

### Типичные ошибки

| Правило                                              | Вердикт                                                                            | Где реализовать (если нет)                                                |
|------------------------------------------------------|------------------------------------------------------------------------------------|---------------------------------------------------------------------------|
| `AvoidInlineConditionals`                            | Нет готового решения — полного аналога нет                                         | Кастомный PHPCS sniff (`SlevomatCodingStandard` запрещает только `?:`)    |
| `CovariantEquals`                                    | Неактуально — Java-специфично                                                      | —                                                                         |
| `EqualsHashCode`                                     | Неактуально — в PHP нет контракта `equals/hashCode`                                | —                                                                         |
| `FinalLocalVariable`                                 | Нет готового решения — в PHP нет `final` для переменных                            | Неавтоматизируемо — языковое ограничение                                  |
| `HiddenField`                                        | Есть — **PHPStan** (shadowed variable detection)                                   | —                                                                         |
| `IllegalInstantiation`                               | Нет готового решения                                                               | Кастомный PHPStan rule                                                    |
| `IllegalToken` (POST_INC, POST_DEC)                  | Есть — **Slevomat** `DisallowIncrementAndDecrementOperators`                       | —                                                                         |
| `InnerAssignment`                                    | Нет готового решения — PHPStan частично                                            | Кастомный PHPStan rule                                                    |
| `MissingSwitchDefault`                               | Есть — **PHPStan** (exhaustiveness check) + **PHP-CS-Fixer**                       | —                                                                         |
| `ModifiedControlVariable`                            | Нет готового решения                                                               | Кастомный PHPStan rule                                                    |
| `SimplifyBooleanExpression`, `SimplifyBooleanReturn` | Есть — **PHP-CS-Fixer** (`simplified_null_return`, `return_assignment`)            | —                                                                         |
| `StringLiteralEquality`                              | Есть — **PHPStan** strict comparison (аналог `===` vs `==`)                        | —                                                                         |
| `NestedForDepth`, `NestedIfDepth`, `NestedTryDepth`  | Нет готового решения — PDDepend устарел                                            | —                                                                         |
| `NoClone`                                            | Нет готового решения — запрет `__clone()` не стандартизован                        | Кастомный PHPStan rule                                                    |
| `NoFinalizer`, `SuperFinalize`                       | Неактуально — в PHP нет `finalize()`                                               | —                                                                         |
| `SuperClone`                                         | Неактуально — в PHP нет `super`                                                    | —                                                                         |
| `IllegalCatch`, `IllegalThrows`                      | Нет готового решения                                                               | Кастомный PHPStan rule                                                    |
| `PackageDeclaration`                                 | Неактуально — в PHP нет package, есть namespace                                    | —                                                                         |
| `ReturnCount` (max=1)                                | Нет готового решения — **PHPMD** есть `ExitExpression`, но не `ReturnCount`        | Кастомный PHPStan rule                                                    |
| `IllegalType`                                        | Нет готового решения                                                               | Кастомный PHPStan rule                                                    |
| `DeclarationOrder`                                   | Есть — **PHP-CS-Fixer** (`ordered_class_elements`)                                 | —                                                                         |
| `ParameterAssignment`                                | Нет готового решения — запрет переприсвоения параметров                            | Кастомный PHPStan rule                                                    |
| `ExplicitInitialization`                             | Нет готового решения                                                               | Кастомный PHPStan rule                                                    |
| `DefaultComesLast`                                   | Есть — **PHP-CS-Fixer** (`switch_case_sort_order`)                                 | —                                                                         |
| `FallThrough`                                        | Есть — **PHP-CS-Fixer** (`no_break_comment`)                                       | —                                                                         |
| `MultipleVariableDeclarations`                       | Есть — **PHP-CS-Fixer**                                                            | —                                                                         |
| `RequireThis`                                        | Неактуально — в PHP `$this->` обязателен синтаксически                             | —                                                                         |
| `UnnecessaryParentheses`                             | Есть — **PHP-CS-Fixer** (`no_unneeded_parentheses`)                                | —                                                                         |
| `OneStatementPerLine`                                | Есть — **PHP-CS-Fixer**                                                            | —                                                                         |

### Импорты

| Правило             | Вердикт                                                                           | Где реализовать (если нет) |
|---------------------|-----------------------------------------------------------------------------------|----------------------------|
| `AvoidStarImport`   | Есть — **PHP-CS-Fixer** (`no_unused_imports`); wildcard `use` PHP не поддерживает | —                          |
| `AvoidStaticImport` | Неактуально — в PHP нет `import static`                                           | —                          |
| `IllegalImport`     | Нет готового решения                                                              | Кастомный PHPStan rule     |
| `RedundantImport`   | Есть — **PHP-CS-Fixer** (`no_unused_imports`)                                     | —                          |
| `ImportOrder`       | Есть — **PHP-CS-Fixer** (`ordered_imports`)                                       | —                          |
| `NoLineWrap`        | Есть — **PHP-CS-Fixer**                                                           | —                          |

### PHPDoc (аналог Javadoc)

| Правило           | Вердикт                                                                                  | Где реализовать (если нет)     |
|-------------------|------------------------------------------------------------------------------------------|--------------------------------|
| `JavadocType`     | Нет готового решения — PHPStan/Psalm валидируют типы в PHPDoc, но не требуют его наличия | Кастомный PHPCS sniff          |
| `JavadocMethod`   | Нет готового решения — нет инструмента, требующего PHPDoc на каждом методе               | Кастомный PHPCS sniff          |
| `JavadocVariable` | Нет готового решения                                                                     | Кастомный PHPCS sniff          |
| `JavadocStyle`    | Нет готового решения — стиль PHPDoc не проверяется автоматически                         | Кастомный PHPCS sniff          |
| `AtclauseOrder`   | Нет готового решения — порядок `@param/@return/@throws` не проверяется                   | Кастомный PHPCS sniff          |

### Метрики

| Правило                          | Вердикт                                                                                        | Где реализовать (если нет)   |
|----------------------------------|------------------------------------------------------------------------------------------------|------------------------------|
| `BooleanExpressionComplexity`    | Нет готового решения — PHPMD устарел, живой замены нет                                         | Кастомный PHPStan rule       |
| `ClassFanOutComplexity` (max 30) | Нет готового решения — PDDepend устарел                                                        | Кастомный PHPStan rule       |
| `CyclomaticComplexity`           | Нет готового решения — PDDepend устарел, PHPMD устарел                                         | Кастомный PHPStan rule       |
| `NPathComplexity`                | Нет готового решения — PDDepend устарел                                                        | Кастомный PHPStan rule       |
| `JavaNCSS`                       | Нет готового решения — PHPLOC заархивирован                                                    | Кастомный PHPStan rule       |

### Прочее

| Правило                              | Вердикт                                                                | Где реализовать (если нет)  |
|--------------------------------------|------------------------------------------------------------------------|-----------------------------|
| `TodoComment`                        | Нет готового решения — **Typos** ищет опечатки, но не TODO-комментарии | Кастомный PHPCS sniff       |
| `UpperEll`                           | Неактуально — Java-специфично (`1l` vs `1L`)                           | —                           |
| `ArrayTypeStyle`                     | Есть — **PHP-CS-Fixer** (`array_syntax`)                               | —                           |
| `FinalParameters`                    | Нет готового решения — в PHP параметры нельзя объявить `final`         | Неавтоматизируемо           |
| `Indentation`, `CommentsIndentation` | Есть — **PHP-CS-Fixer**                                                | —                           |
| `TrailingComment`                    | Нет готового решения                                                   | Кастомный PHPCS sniff       |
| `OuterTypeFilename`                  | Есть — **PHPCS** (PSR-1: имя файла = имя класса)                       | —                           |

### Модификаторы

| Правило             | Вердикт                                                                   | Где реализовать (если нет) |
|---------------------|---------------------------------------------------------------------------|----------------------------|
| `ModifierOrder`     | Есть — **PHP-CS-Fixer** (`visibility_required`, `ordered_class_elements`) | —                          |
| `RedundantModifier` | Есть — **PHP-CS-Fixer**                                                   | —                          |

### Именование

| Правило                                                               | Вердикт                                                                   | Где реализовать (если нет)   |
|-----------------------------------------------------------------------|---------------------------------------------------------------------------|------------------------------|
| `AbbreviationAsWordInName`                                            | Нет готового решения                                                      | Кастомный PHPCS sniff        |
| `ClassTypeParameterName`                                              | Неактуально — PHP generics не существуют                                  | —                            |
| `ConstantName` (UPPER_CASE)                                           | Есть — **PHPCS**                                                          | —                            |
| `LocalFinalVariableName`, `LocalVariableName` (`^(id\|[a-z]{3,12})$`) | Нет готового решения — нет инструмента с нужным паттерном                 | Кастомный PHPCS sniff        |
| `CatchParameterName`                                                  | Нет готового решения                                                      | Кастомный PHPCS sniff        |
| `MemberName`                                                          | Нет готового решения                                                      | Кастомный PHPCS sniff        |
| `MethodName`                                                          | Есть — **PHP-CS-Fixer** (camelCase через PER-CS) + **PHPCS**              | —                            |
| `ParameterName`                                                       | Нет готового решения — min длина не проверяется                           | Кастомный PHPCS sniff        |
| `TypeName` (PascalCase)                                               | Есть — **PHPCS** (PSR-1)                                                  | —                            |

### Размеры

| Правило                             | Вердикт                                                                              | Где реализовать (если нет)   |
|-------------------------------------|--------------------------------------------------------------------------------------|------------------------------|
| `ExecutableStatementCount` (max 40) | Нет готового решения — PDDepend устарел                                              | Кастомный PHPStan rule       |
| `AnonInnerLength`                   | Неактуально — в PHP нет анонимных внутренних классов                                 | —                            |
| `MethodLength`                      | Нет готового решения — PHPLOC заархивирован                                          | Кастомный PHPStan rule       |
| `ParameterNumber` (max 3)           | Нет готового решения                                                                 | Кастомный PHPStan rule       |
| `OuterTypeNumber`                   | Неактуально — в PHP один класс на файл по PSR                                        | —                            |
| `MethodCount`                       | Нет готового решения — PHPLOC заархивирован, PDDepend устарел                        | Кастомный PHPStan rule       |

### Пробелы

| Правило                                                               | Вердикт                                                 | Где реализовать (если нет) |
|-----------------------------------------------------------------------|---------------------------------------------------------|----------------------------|
| `GenericWhitespace`, `MethodParamPad`, `ParenPad`, `TypecastParenPad` | Есть — **PHP-CS-Fixer**                                 | —                          |
| `EmptyForInitializerPad`, `EmptyForIteratorPad`                       | Есть — **PHP-CS-Fixer**                                 | —                          |
| `NoWhitespaceAfter`, `NoWhitespaceBefore`                             | Есть — **PHP-CS-Fixer**                                 | —                          |
| `OperatorWrap`                                                        | Есть — **PHP-CS-Fixer** (`binary_operator_spaces`)      | —                          |
| `WhitespaceAfter`, `WhitespaceAround`                                 | Есть — **PHP-CS-Fixer**                                 | —                          |
| `EmptyLineSeparator`                                                  | Есть — **PHP-CS-Fixer** (`class_attributes_separation`) | —                          |

---

## 3. Кастомные Checkstyle-чекеры

| Класс                                         | Вердикт                                                           | Где реализовать (если нет)  |
|-----------------------------------------------|-------------------------------------------------------------------|-----------------------------|
| `BracketsStructureCheck`                      | Есть — **PHP-CS-Fixer**                                           | —                           |
| `CurlyBracketsStructureCheck`                 | Есть — **PHP-CS-Fixer**                                           | —                           |
| `EmptyLinesCheck`                             | Есть — **PHP-CS-Fixer** (`no_extra_blank_lines`)                  | —                           |
| `StringLiteralsConcatenationCheck`            | Нет готового решения                                              | Кастомный PHPStan rule      |
| `MultilineJavadocTagsCheck`                   | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `NoJavadocForOverriddenMethodsCheck`          | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `MethodBodyCommentsCheck`                     | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `MethodsOrderCheck`                           | Есть — **PHP-CS-Fixer** (`ordered_class_elements`)                | —                           |
| `JavadocLocationCheck`                        | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `ConstantUsageCheck`                          | Нет готового решения                                              | Кастомный PHPStan rule      |
| `NonStaticMethodCheck`                        | Нет готового решения — запрет `static` методов не автоматизирован | Кастомный PHPStan rule      |
| `ProtectedMethodInFinalClassCheck`            | Нет готового решения                                              | Кастомный PHPStan rule      |
| `FinalSemicolonInTryWithResourcesCheck`       | Неактуально — в PHP нет try-with-resources                        | —                           |
| `JavadocEmptyLineCheck`                       | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `DiamondOperatorCheck`                        | Неактуально — в PHP нет diamond operator                          | —                           |
| `JavadocParameterOrderCheck`                  | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `JavadocTagsCheck`                            | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `SingleLineCommentCheck` (C-style запрет)     | Нет готового решения — **PHPCS** частично                         | Кастомный PHPCS sniff       |
| `SingleLineCommentCheck` (заглавная буква)    | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `MultiLineCommentCheck` (заглавная буква)     | Нет готового решения                                              | Кастомный PHPCS sniff       |
| `ImportCohesionCheck`                         | Нет готового решения                                              | Кастомный PHPStan rule      |
| `CascadeIndentationCheck`                     | Есть — **PHP-CS-Fixer**                                           | —                           |
| `ConditionalRegexpMultilineCheck` (ArrayList) | Неактуально                                                       | —                           |

---

## 4. PMD

### Категории

| Категория        | Вердикт                                                                       | Где реализовать (если нет) |
|------------------|-------------------------------------------------------------------------------|----------------------------|
| `bestpractices`  | Есть — **PHPStan** level 9 + **Psalm** level 1 покрывают большинство          | —                          |
| `codestyle`      | Есть — **PHP-CS-Fixer** + **PHPCS**                                           | —                          |
| `design`         | Частично — **ergebnis/phpstan-rules** (FinalRule); остальное кастомный PHPStan | Кастомный PHPStan rule     |
| `documentation`  | Нет готового решения — обязательность PHPDoc не проверяется                   | Кастомные PHPCS sniffs     |
| `errorprone`     | Есть — **PHPStan** + **Psalm**                                                | —                          |
| `performance`    | Нет готового решения — PHP-специфичные паттерны производительности не покрыты | Кастомный PHPStan rule     |
| `multithreading` | Неактуально — PHP синхронный                                                  | —                          |

### Кастомные PMD-правила

| Правило                                             | Вердикт                                                                    | Где реализовать (если нет)                         |
|-----------------------------------------------------|----------------------------------------------------------------------------|----------------------------------------------------|
| `ProhibitPlainJunitAssertionsRule`                  | Есть — архитектура проекта (кастомные Constraint-объекты вместо `assert*`) | —                                                  |
| `UseStringIsEmptyRule`                              | Есть — **PHPStan** + **Psalm** (strict mode)                               | —                                                  |
| `UnnecessaryLocalRule`                              | Нет готового решения                                                       | Кастомный PHPStan rule                             |
| `ConstructorShouldDoInitialization`                 | Нет готового решения — соглашение Elegant Objects, не автоматизировано     | Кастомный PHPStan rule (сложно, AST-анализ тела)   |
| `OnlyOneConstructorShouldDoInitialization`          | Неактуально — в PHP один `__construct()`                                   | —                                                  |
| `ConstructorOnlyInitializesOrCallOtherConstructors` | Нет готового решения — соглашение проекта                                  | Кастомный PHPStan rule (сложно, AST-анализ тела)   |
| `AvoidDirectAccessToStaticFields`                   | Нет готового решения                                                       | Кастомный PHPStan rule                             |
| `AvoidAccessToStaticMembersViaThis`                 | Нет готового решения                                                       | Кастомный PHPStan rule                             |
| `ProhibitPublicStaticMethods`                       | Нет готового решения — Elegant Objects запрещает `static`, инструмента нет | Кастомный PHPStan rule                             |
| `ProhibitFilesCreateFileInTests`                    | Нет готового решения — в Piqule есть `TempFolder`-фикстура по соглашению   | Кастомный PHPStan rule                             |
| `JUnitTestClassShouldBeFinal`                       | Нет готового решения — конвенция проекта                                   | Кастомный PHPStan rule (или через `ergebnis/rules`) |

### Переопределённые правила

| Правило                          | Вердикт                                                      | Где реализовать (если нет) |
|----------------------------------|--------------------------------------------------------------|----------------------------|
| `AvoidDuplicateLiterals` (max 5) | Частично — **PHPStan** (strict mode, повторяющиеся литералы частично)        | —                          |

---

## 5. Maven-validators

| Правило                                 | Вердикт                                                                          | Где реализовать (если нет) |
|-----------------------------------------|----------------------------------------------------------------------------------|----------------------------|
| `EnforcerValidator` (версии Maven/Java) | Неактуально — аналог: проверка версии PHP в CI-матрице                           | —                          |
| `DuplicateFinderValidator`              | Нет готового решения — нет PHP-аналога для дублирующихся файлов в classpath      | Кастомный PHPStan rule     |
| `DependenciesValidator`                 | Нет готового решения — Composer не анализирует использование зависимостей в коде | Кастомный PHPStan rule     |
| `SnapshotsValidator`                    | Нет готового решения — `dev-master` как аналог SNAPSHOT; инструмента нет         | Кастомный PHPStan rule     |
| `PomXpathValidator`                     | Неактуально — специфично для pom.xml                                             | —                          |
| `SvnPropertiesValidator`                | Неактуально — проект на Git                                                      | —                          |

---

## Сводка по покрытию

| Категория               | Покрыто | Не покрыто | Неактуально |
|-------------------------|---------|------------|-------------|
| Файловые проверки       | 6       | 3          | 5           |
| Стиль кода (TreeWalker) | 19      | 13         | 12          |
| Метрики                 | 0       | 6          | 0           |
| PHPDoc                  | 0       | 5          | 0           |
| Именование              | 3       | 5          | 1           |
| Размеры                 | 0       | 4          | 2           |
| Пробелы                 | 8       | 0          | 0           |
| Кастомные Checkstyle    | 4       | 13         | 5           |
| PMD категории           | 4       | 2          | 1           |
| PMD кастомные           | 2       | 8          | 1           |
| Maven-validators        | 0       | 3          | 3           |

**Главные пробелы:**

- **PHPDoc** — обязательность и стиль не проверяются ни одним инструментом
- **Конструкторы** — правила Elegant Objects (только инициализация, запрет вычислений) не автоматизированы
- **`static`** — запрет `public static` методов не реализован инструментально
- **`ReturnCount` (max=1)**, **`ParameterAssignment`**, **`NoClone`** — нет PHP-аналогов в существующих инструментах

---

## Где реализовать — сводка по инструментам

> **Итоговое решение:** один пакет — PHPStan extension. PHPCS не нужен. Все проверки, включая PHPDoc-стиль, реализуются в PHPStan через `phpstan/phpdoc-parser` (встроен). Все метрики — через AST (`getStartLine()`/`getEndLine()`, подсчёт узлов). Внешние пакеты не нужны — каждое правило это 30–100 строк кода.

### Кастомные PHPStan rules — полный список

| Что реализовать | Группа |
|---|---|
| `ReturnShouldStartWithCapitalLetter` (текст @return description) | PHPDoc-стиль |
| `ParamShouldStartWithCapitalLetter` (текст @param description) | PHPDoc-стиль |
| `JavadocStyle` (заглавная буква в summary) | PHPDoc-стиль |
| `AtclauseOrder` (порядок @param/@return/@throws) | PHPDoc-стиль |
| `JavadocParameterOrderCheck` (порядок @param = порядок аргументов) | PHPDoc-стиль |
| `JavadocMethod` / `JavadocVariable` (обязательность PHPDoc) | PHPDoc-стиль |
| `JavadocEmptyLineCheck` / `MultilineJavadocTagsCheck` | PHPDoc-стиль |
| `NoJavadocForOverriddenMethodsCheck` | PHPDoc-стиль |
| `SingleLineCommentCheck` (заглавная буква, запрет `//` C-style) | Комментарии |
| `MultiLineCommentCheck` (заглавная буква) | Комментарии |
| `TrailingComment` (комментарий после кода на строке) | Комментарии |
| `TodoComment` | Комментарии |
| `MethodBodyCommentsCheck` | Комментарии |
| `FileLength` (max строк в файле) | Метрики |
| `MethodLength` (max строк метода) | Метрики |
| `MethodCount` (max методов в классе) | Метрики |
| `ClassFanOutComplexity` (max зависимостей класса) | Метрики |
| `CyclomaticComplexity` | Метрики |
| `BooleanExpressionComplexity` | Метрики |
| `ParameterNumber` (max 3) | Метрики |
| `FinalClass` | Дизайн |
| `HideUtilityClassConstructor` | Дизайн |
| `MutableException` | Дизайн |
| `ThrowsCount` | Дизайн |
| `ProtectedMethodInFinalClassCheck` | Дизайн |
| `NonStaticMethodCheck` / `ProhibitPublicStaticMethods` | Дизайн |
| `AvoidDirectAccessToStaticFields` / `AvoidAccessToStaticMembersViaThis` | Дизайн |
| `ConstructorShouldDoInitialization` / `ConstructorOnlyInitializes...` | Дизайн |
| `AbbreviationAsWordInName` | Именование |
| `LocalVariableName` / `CatchParameterName` / `MemberName` / `ParameterName` | Именование |
| `ReturnCount` (max=1) | Типичные ошибки |
| `ParameterAssignment` | Типичные ошибки |
| `NoClone` | Типичные ошибки |
| `IllegalCatch` / `IllegalThrows` | Типичные ошибки |
| `IllegalType` | Типичные ошибки |
| `InnerAssignment` | Типичные ошибки |
| `ModifiedControlVariable` | Типичные ошибки |
| `IllegalInstantiation` | Типичные ошибки |
| `ExplicitInitialization` | Типичные ошибки |
| `AvoidInlineConditionals` (запрет тернарного `? :`) | Типичные ошибки |
| `AvoidNestedBlocks` | Типичные ошибки |
| `MissingDeprecated` | Аннотации |
| `AnnotationUseStyle` | Аннотации |
| `EndOfLineSymbolLimits` / `StartLineSymbolLimits` | Прочее |
| `ConstantUsageCheck` | Прочее |
| `UnnecessaryLocalRule` / `StringLiteralsConcatenationCheck` | Прочее |
| `ImportCohesionCheck` | Прочее |
| `ProhibitFilesCreateFileInTests` / `JUnitTestClassShouldBeFinal` | Прочее |

**Итого: ~38 кастомных PHPStan rules**

### Итоговый счёт

| Инструмент | Уже есть | Написать кастомное | Неавтоматизируемо |
|---|---|---|---|
| **PHP-CS-Fixer** | 25+ | — | — |
| **PHPCS** (PSR-1/PSR-12) | 5 | — | — |
| **PHPStan** (base + strict-rules) | 8 | ~38 rules | — |
| **Psalm** | 3 | — | — |
| **Неавтоматизируемо** | — | — | 2 (FinalLocalVariable, FinalParameters) |

**Вывод:**
- Один PHPStan extension (~38 rules) — единственный кастомный пакет, нулевые внешние зависимости
- Cyclomatic complexity: +1 за каждый `if/for/foreach/while/case/catch/&&/||/?:/??` — ~40 строк, пишем сами
- Внешние пакеты (ergebnis, Slevomat, pdepend, phploc, cognitive-complexity) не нужны
