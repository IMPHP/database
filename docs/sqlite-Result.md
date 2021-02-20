# [Database](db.md) / [SQLite3](sqlite.md) / Result
 > im\database\sqlite3\Result
____

## Description
Result class for `SQLite` databases

## Synopsis
```php
class Result extends im\database\BaseResult implements im\database\Result {

    // Methods
    public __construct(SQLite3Result $result, int $numRows = -1)
    public length(): int
    public empty(): bool
    public seek(int $pos): bool
    public destroy(bool $cache = FALSE): void
    public fetchAssoc(bool $destroy = FALSE): null|im\util\Map
    public fetchRow(bool $destroy = FALSE): null|im\util\Vector

    // Inherited Methods
    public fetchColumn(string|int $key = 0, bool $destroy = FALSE): mixed
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Result&nbsp;::&nbsp;\_\_construct__](sqlite-Result-__construct.md) | Creates a result instance for an `SQLite` query |
| [__Result&nbsp;::&nbsp;length__](sqlite-Result-length.md) |  |
| [__Result&nbsp;::&nbsp;empty__](sqlite-Result-empty.md) |  |
| [__Result&nbsp;::&nbsp;seek__](sqlite-Result-seek.md) |  |
| [__Result&nbsp;::&nbsp;destroy__](sqlite-Result-destroy.md) |  |
| [__Result&nbsp;::&nbsp;fetchAssoc__](sqlite-Result-fetchAssoc.md) |  |
| [__Result&nbsp;::&nbsp;fetchRow__](sqlite-Result-fetchRow.md) |  |
| [__Result&nbsp;::&nbsp;fetchColumn__](sqlite-Result-fetchColumn.md) | Fetch column in the current row and move pointer to the next |
