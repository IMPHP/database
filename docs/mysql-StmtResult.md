# [Database](db.md) / [MySQLi](mysql.md) / StmtResult
 > im\database\mysqli\StmtResult
____

## Description
Result class for `MySQL` databases using STMT objects.

 > The `mysqlnd` driver includes a 'get_result()' method in it's stmt object. It returns a full featured result object that can be used just as the one returned using the normal query method.  

## Synopsis
```php
class StmtResult extends im\database\BaseResult implements im\database\Result {

    // Methods
    public __construct(mysqli_stmt $stmt)
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
| [__StmtResult&nbsp;::&nbsp;\_\_construct__](mysql-StmtResult-__construct.md) |  |
| [__StmtResult&nbsp;::&nbsp;length__](mysql-StmtResult-length.md) |  |
| [__StmtResult&nbsp;::&nbsp;empty__](mysql-StmtResult-empty.md) |  |
| [__StmtResult&nbsp;::&nbsp;destroy__](mysql-StmtResult-destroy.md) |  |
| [__StmtResult&nbsp;::&nbsp;seek__](mysql-StmtResult-seek.md) |  |
| [__StmtResult&nbsp;::&nbsp;fetchAssoc__](mysql-StmtResult-fetchAssoc.md) |  |
| [__StmtResult&nbsp;::&nbsp;fetchRow__](mysql-StmtResult-fetchRow.md) |  |
| [__StmtResult&nbsp;::&nbsp;fetchColumn__](mysql-StmtResult-fetchColumn.md) | Fetch column in the current row and move pointer to the next |
