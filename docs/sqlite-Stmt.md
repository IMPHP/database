# [Database](db.md) / [SQLite3](sqlite.md) / Stmt
 > im\database\sqlite3\Stmt
____

## Description
Prepared Statements class for `SQLite` databases

## Synopsis
```php
class Stmt implements im\database\Stmt {

    // Methods
    public __construct(im\database\sqlite3\Connection $conn, string $sql, bool $queryCount = FALSE)
    public enquire(mixed &...$data): null|im\database\Result
    public execute(mixed &...$data): int
    public close(): void
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Stmt&nbsp;::&nbsp;\_\_construct__](sqlite-Stmt-__construct.md) | Creates a new prepared statement |
| [__Stmt&nbsp;::&nbsp;enquire__](sqlite-Stmt-enquire.md) |  |
| [__Stmt&nbsp;::&nbsp;execute__](sqlite-Stmt-execute.md) |  |
| [__Stmt&nbsp;::&nbsp;close__](sqlite-Stmt-close.md) |  |
