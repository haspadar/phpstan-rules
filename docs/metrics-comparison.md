# Сравнение метрик с другими инструментами

Для каждой метрики: дефолты и опции в других инструментах, и что реализовано или предлагается у нас.

Статусы: ✅ Реализовано · ⬜ Планируется

---

## MethodLengthRule — длина метода ✅

**Реализовано.** Дефолты: `maxLines=100`, `skipBlankLines=false`, `skipComments=false`.

| Инструмент | Правило | Дефолт | Единица | Пустые строки | Комментарии | Опции |
|---|---|---|---|---|---|---|
| Checkstyle | `MethodLength` | **150** | строки | считаются | считаются | `countEmpty` (оба вместе) |
| PMD | `ExcessiveMethodLength` | **100** | строки | считаются | считаются | нет |
| PMD 6+ | `NcssCount` | **12** | NCSS statements | N/A | N/A | `ncssOptions` |
| ESLint | `max-lines-per-function` | **50** | строки | считаются | считаются | `skipBlankLines`, `skipComments` (раздельно) |
| Pylint | `too-many-statements` | **50** | statements | N/A | N/A | `max-statements` |
| RuboCop | `Metrics/MethodLength` | **10** | строки | считаются | **не считаются** | `CountComments`, `CountAsOne` |
| PHPMD | `ExcessiveMethodLength` | **100** | строки | считаются | считаются | нет |

**Наши дефолты:**
- `maxLines=100` — совпадает с PHPMD; мягче ESLint (50), строже Checkstyle (150)
- `skipBlankLines=false`, `skipComments=false` — как ESLint; единственный инструмент с раздельными опциями для каждого
- RuboCop исключает комментарии по умолчанию (`CountComments: false`), аргументируя что они документация; наша позиция: комментарии внутри метода — признак сложности, скрывать их из метрики нечестно
- PMD переход с `ExcessiveMethodLength` (100) на `NcssCount` (12) при смене метрики вызвал волну недовольства в сообществе

---

## FileLengthRule — длина файла ✅

**Реализовано.** Дефолты: `maxLines=1000`, `skipBlankLines=false`, `skipComments=false`.

| Инструмент | Правило | Дефолт | Единица | Пустые строки | Комментарии | Опции |
|---|---|---|---|---|---|---|
| Checkstyle | `FileLength` | **2000** | строки | считаются | считаются | `fileExtensions` |
| Qulice | `FileLength` | **1000** | строки | считаются | считаются | `fileExtensions: java` |
| ESLint | `max-lines` | **300** | строки | считаются | считаются | `skipBlankLines`, `skipComments` (раздельно) |
| Pylint | `too-many-lines` (C0302) | **1000** | строки | считаются | не задокументировано | `max-module-lines` |
| RuboCop | `Metrics/ClassLength` | **100** | строки кода | ? | **не считаются** (`CountComments: false`) | `CountComments`, `CountAsOne` |
| PHPMD | `ExcessiveClassLength` | **1000** | строки | не считаются (`ignore-whitespace`) | **PHPDoc — считаются**, inline — нет (NCSS) | `ignore-whitespace` |

**Предлагаемые дефолты:**
- `maxLines=1000` — совпадает с Qulice/Pylint/PHPMD; PHP-файл с одним классом редко должен превышать 1000 строк
- Опции `skipBlankLines`/`skipComments` — по аналогии с MethodLengthRule (единая модель)
- Checkstyle и ESLint считают все строки (комментарии + пустые); ESLint единственный с раздельными `skipBlankLines`/`skipComments` на уровне файла
- PHPMD использует NCSS-алгоритм: inline-комментарии не считаются, PHPDoc-блоки — считаются
- Наша позиция: считать всё (как в MethodLengthRule) — честнее отражает размер файла
- RuboCop `CountAsOne` — элегантная фича (массив/хэш считается одной строкой), но в PHP AST это нетривиально; откладываем

---

## TooManyMethodsRule — число методов в классе ✅

**Реализовано.** Дефолты: `maxMethods=20`, `onlyPublic=false`.

| Инструмент | Правило | Дефолт | Опции |
|---|---|---|---|
| Checkstyle | `MethodCount` | **100** (maxTotal) | `maxPublic`, `maxPrivate`, `maxProtected` — раздельно по видимости |
| PMD | `TooManyMethods` | **10** | `maxmethods`; игнорирует `get*/set*` по умолчанию |
| Pylint | `too-many-public-methods` (R0904) | **20** | `max-public-methods` |
| PHPMD | `TooManyMethods` | **25** | `maxmethods`; `ignorepattern` для аксессоров |

**Предлагаемые дефолты:**
- `maxMethods=20` — совпадает с Pylint; PMD (10) слишком строго, Checkstyle (200) бессмысленно мягко
- Не игнорировать геттеры/сеттеры по умолчанию: в Elegant Objects аксессоры — запах, их наличие должно отражаться в метрике
- Раздельные лимиты по видимости (как в Checkstyle) — избыточная сложность для первой версии

---

## ParameterNumberRule — число параметров метода ⬜

**Планируется.** Предлагаемые дефолты: `maxParameters=3`.

| Инструмент | Правило | Дефолт | Опции |
|---|---|---|---|
| Checkstyle | `ParameterNumber` | **7** | `ignoreOverriddenMethods: false` |
| Qulice | `ParameterNumber` | **3** | `ignoreOverriddenMethods: true` |
| PMD | `ExcessiveParameterList` | **10** | `minimum` |
| ESLint | `max-params` | **3** | `max` |
| Pylint | `too-many-arguments` (R0913) | **5** | `max-args`, `ignored-argument-names` |
| RuboCop | `Metrics/ParameterLists` | **5** | `CountKeywordArgs: true`, `MaxOptionalParameters: 3` |
| PHPMD | `ExcessiveParameterList` | **10** | `minimum` |

**Предлагаемые дефолты:**
- `maxParameters=3` — совпадает с ESLint; Qulice тоже устанавливает max=3; Checkstyle (7) и PMD (10) слишком мягко
- Если метод требует более 3 параметров — сигнал для введения Value Object или Builder
- `ignoreOverriddenMethods` — разумная опция (Checkstyle); Qulice устанавливает `ignoreOverriddenMethods=true`, стоит добавить для `__construct` и `#[Override]`-методов

---

## CyclomaticComplexityRule — цикломатическая сложность ⬜

**Планируется.** Предлагаемые дефолты: `maxComplexity=5`.

| Инструмент | Правило | Дефолт | Единица | Опции |
|---|---|---|---|---|
| Checkstyle | `CyclomaticComplexity` | **10** | score | `switchBlockAsSingleDecision: false`, `tokens` (какие конструкции считать) |
| PMD | `CyclomaticComplexity` | **10** (method) | score | `methodReportLevel`, `classReportLevel` |
| ESLint | `complexity` | **20** | score | `max` |
| Pylint | `too-complex` (R1260) | **10** | score | `max-complexity`; опциональное расширение, не включено по умолчанию |
| RuboCop | `Metrics/CyclomaticComplexity` | **7** | score | `Max`; считает `if`, `unless`, `while`, `until`, `for`, `&&`, `\|\|`, `rescue`, `case/when` |
| PHPMD | `CyclomaticComplexity` | **10** | score | `reportLevel`, `showClassesComplexity`, `showMethodsComplexity` |

**Предлагаемые дефолты:**
- `maxComplexity=5` — строже большинства инструментов (дефолт 10); Qulice не переопределяет, использует Checkstyle-дефолт (10); но для PHP принимаем 5 — при CC>5 метод почти всегда стоит разбить
- ESLint (20) — аномально мягко, не подходит
- Считать: `if`, `elseif`, `while`, `for`, `foreach`, `case`, `catch`, `&&`, `||`, `?:`, `??` — каждый +1 к базовой сложности 1
- `switchBlockAsSingleDecision` (Checkstyle) — не нужно: PHP `match` уже покрыт PHPStan exhaustiveness check

---

## ClassFanOutRule — число зависимостей класса ⬜

**Планируется.** Предлагаемые дефолты: `maxFanOut=10`.

| Инструмент | Правило | Дефолт | Опции |
|---|---|---|---|
| Checkstyle | `ClassFanOutComplexity` | **20** | `excludedClasses` (длинный список JDK-типов), `excludedPackages`, `excludeClassesRegexps` |
| Qulice | `ClassFanOutComplexity` | **30** | `excludedClasses` не задан явно |
| PMD | `CouplingBetweenObjects` | **20** | `threshold` |
| PHPMD | `CouplingBetweenObjects` | **13** | `maximum` |

**Предлагаемые дефолты:**
- `maxFanOut=10` — строже Checkstyle/PMD (20) и PHPMD (13); класс с 10+ зависимостями нарушает SRP
- `excludedClasses` — нужен список PHP-стандартных классов, которые не считаются (например, `DateTime`, `Exception`, `stdClass`); по аналогии с Checkstyle
- RuboCop не имеет аналога

---

## BooleanExpressionComplexityRule — сложность булевых выражений ⬜

**Планируется.** Предлагаемые дефолты: `maxOperators=3`.

| Инструмент | Правило | Дефолт | Опции |
|---|---|---|---|
| Checkstyle | `BooleanExpressionComplexity` | **3** | `tokens`: LAND (`&&`), BAND (`&`), LOR (`\|\|`), BOR (`\|`), BXOR (`^`) — раздельно |
| ESLint | — | — | нет аналога |
| RuboCop | — | — | нет аналога |
| PMD | — | — | нет аналога |
| Pylint | — | — | нет аналога |
| PHPMD | — | — | нет аналога |

**Предлагаемые дефолты:**
- `maxOperators=3` — совпадает с Checkstyle; Checkstyle — единственный инструмент с этим правилом
- Считать: `&&`, `||`, `and`, `or` в одном выражении
- `&`, `|`, `^` (битовые) — спорно для PHP; предлагается не считать по умолчанию (отличие от Checkstyle, где они включены)
- Если выражение сложнее — извлечь в именованный метод или переменную
