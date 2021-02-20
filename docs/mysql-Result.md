# [Database](db.md) / [MySQLi](mysql.md) / Result
 > im\database\mysqli\Result
____

## Description
Result class for `MySQL` databases.

## Synopsis
```php
class Result extends im\database\BaseResult implements im\database\Result {

    // Methods
    public __construct(mysqli_result $result)
    public length(): int
    public empty(): bool
    public destroy(bool $cache = FALSE): void
    public seek(int $pos): bool
    public fetchAssoc(bool $destroy = FALSE): null|im\util\Map
    public fetchRow(bool $destroy = FALSE): null|im\util\Vector

    // Inherited Methods
    public fetchColumn(string|int $key = 0, bool $destroy = FALSE): mixed
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Result&nbsp;::&nbsp;\_\_construct__](mysql-Result-__construct.md) |  |
| [__Result&nbsp;::&nbsp;length__](mysql-Result-length.md) |  |
| [__Result&nbsp;::&nbsp;empty__](mysql-Result-empty.md) |  |
| [__Result&nbsp;::&nbsp;destroy__](mysql-Result-destroy.md) |  |
| [__Result&nbsp;::&nbsp;seek__](mysql-Result-seek.md) |  |
| [__Result&nbsp;::&nbsp;fetchAssoc__](mysql-Result-fetchAssoc.md) |  |
| [__Result&nbsp;::&nbsp;fetchRow__](mysql-Result-fetchRow.md) |  |
| [__Result&nbsp;::&nbsp;fetchColumn__](mysql-Result-fetchColumn.md) | Fetch column in the current row and move pointer to the next |
