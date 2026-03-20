# FileLengthRule

## Что проверяет

Правило считает количество строк в PHP-файле и сообщает об ошибке, если оно превышает допустимый лимит.

Error identifier: `haspadar.fileLines`

---

## Опции

| Опция            | Тип    | По умолчанию | Описание                          |
|------------------|--------|--------------|-----------------------------------|
| `maxLines`       | `int`  | `1000`       | Максимальное число строк файла    |
| `skipBlankLines` | `bool` | `false`      | Не считать пустые строки          |
| `skipComments`   | `bool` | `false`      | Не считать строки с комментариями |

---

## Сравнение с другими инструментами

| Инструмент                        | Правило  | Дефолт      | Единица      | Пустые строки                  | Комментарии                                  | Опции |
|-----------------------------------|----------|-------------|--------------|--------------------------------|----------------------------------------------|-------|
| **Checkstyle** `FileLength`       | 2000     | строки      | считаются    | считаются                      | `fileExtensions`                             |
| **Qulice** `FileLength`           | 1000     | строки      | считаются    | считаются                      | `fileExtensions: java`                       |
| **ESLint** `max-lines`            | 300      | строки      | считаются    | считаются                      | `skipBlankLines`, `skipComments` (раздельно) |
| **Pylint** `too-many-lines`       | 1000     | строки      | считаются    | не задокументировано           | `max-module-lines`                           |
| **RuboCop** `Metrics/ClassLength` | 100      | строки кода | ?            | **не считаются**               | `CountComments`, `CountAsOne`                |
| **PHPMD** `ExcessiveClassLength`  | 1000     | строки      | не считаются | PHPDoc считаются, inline — нет | `ignore-whitespace`                          |
| **haspadar** `FileLengthRule`     | **1000** | строки      | считаются    | считаются                      | `skipBlankLines`, `skipComments` (раздельно) |

---

## Дефолты и их обоснование

### `maxLines = 1000`

- **Checkstyle** — 2000 строк
- **Qulice/Pylint/PHPMD** — 1000 строк (наш выбор совпадает)
- **ESLint** — 300 строк, строго
- **RuboCop** — 100 строк, очень строго
- 1000 строк — устоявшийся дефолт в Java/PHP-инструментах; PHP-файл крайне редко должен его превышать

### `skipBlankLines = false`

По аналогии с `MethodLinesRule` — единая модель опций. Пустые строки входят в метрику честно: файл, раздутый пустыми
строками, всё равно сигнализирует о проблемах с дизайном.

### `skipComments = false`

По аналогии с `MethodLinesRule`. Комментарии в коде — часть его объёма. Исключение их создаёт ложное ощущение
компактности. Если нужно — опция есть.

---

## Примеры

### Хорошо

```php
// Файл с одним классом, 50–200 строк — нормально
final class OrderProcessor
{
    public function __construct(
        private readonly PaymentGateway $gateway,
        private readonly OrderRepository $orders,
    ) {}

    public function process(Order $order): Receipt
    {
        // ...
    }
}
```

### Плохо (превышен лимит)

```php
// Файл с 600+ строками — сигнал разбить на несколько классов
final class GodClass
{
    // 50 методов, 600 строк...
}
```

---

## Настройка в phpstan.neon

```yaml
services:
  - class: Haspadar\PHPStanRules\Rules\FileLengthRule
    arguments:
      options:
        maxLines: 300
        skipBlankLines: false
        skipComments: false
    tags:
      - phpstan.rules.rule
```

Правило уже зарегистрировано как PHPStan service в `extension.neon` с дефолтными значениями, поэтому при подключении
пакета дополнительная конфигурация не нужна. Секция выше нужна только для переопределения дефолтов.
