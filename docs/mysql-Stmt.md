# [Database](db.md) / [MySQLi](mysql.md) / Stmt
 > im\database\mysqli\Stmt
____

## Description
Prepared Statements class for `MySQL` databases

## Synopsis
```php
class Stmt implements im\database\Stmt {

    // Methods
    public __construct(im\database\mysqli\Connection $conn, string $sql)
    public enquire(mixed &...$data): null|im\database\Result
    public execute(mixed &...$data): int
    public close(): void
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Stmt&nbsp;::&nbsp;\_\_construct__](mysql-Stmt-__construct.md) |  |
| [__Stmt&nbsp;::&nbsp;enquire__](mysql-Stmt-enquire.md) |  |
| [__Stmt&nbsp;::&nbsp;execute__](mysql-Stmt-execute.md) |  |
| [__Stmt&nbsp;::&nbsp;close__](mysql-Stmt-close.md) |  |
