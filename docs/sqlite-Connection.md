# [Database](db.md) / [SQLite3](sqlite.md) / Connection
 > im\database\sqlite3\Connection
____

## Description
Connection class for `SQLite` databases

## Synopsis
```php
class Connection extends im\database\BaseConnection implements im\database\Connection {

    // Methods
    public __construct(null|string $file = NULL, bool $queryCount = FALSE)
    public beginTransaction(): bool
    public rollbackTransaction(): bool
    public commitTransaction(): bool
    public escape(mixed $data): string
    public stmt(string $sql): im\database\Stmt
    public enquire(string $sql, mixed ...$data): null|im\database\Result
    public execute(string $sql, mixed ...$data): int
    public isConnected(): bool
    public driver(): string
    public platform(): string
    public close(): void

    // Inherited Methods
    public embedSQLValues(string $sql, mixed &...$values): string
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Connection&nbsp;::&nbsp;\_\_construct__](sqlite-Connection-__construct.md) | Open/Create SQLite database file |
| [__Connection&nbsp;::&nbsp;beginTransaction__](sqlite-Connection-beginTransaction.md) | Begin a transaction |
| [__Connection&nbsp;::&nbsp;rollbackTransaction__](sqlite-Connection-rollbackTransaction.md) | Rollback any changes made after transactions was started |
| [__Connection&nbsp;::&nbsp;commitTransaction__](sqlite-Connection-commitTransaction.md) | Commit any changes made after transactions was started |
| [__Connection&nbsp;::&nbsp;escape__](sqlite-Connection-escape.md) | Escape a user input value to make it SQL highjack safe  This method converts data types to the appropriate string representations |
| [__Connection&nbsp;::&nbsp;stmt__](sqlite-Connection-stmt.md) | Prepares and returns a prepared statement |
| [__Connection&nbsp;::&nbsp;enquire__](sqlite-Connection-enquire.md) |  |
| [__Connection&nbsp;::&nbsp;execute__](sqlite-Connection-execute.md) |  |
| [__Connection&nbsp;::&nbsp;isConnected__](sqlite-Connection-isConnected.md) |  |
| [__Connection&nbsp;::&nbsp;driver__](sqlite-Connection-driver.md) |  |
| [__Connection&nbsp;::&nbsp;platform__](sqlite-Connection-platform.md) |  |
| [__Connection&nbsp;::&nbsp;close__](sqlite-Connection-close.md) |  |
| [__Connection&nbsp;::&nbsp;embedSQLValues__](sqlite-Connection-embedSQLValues.md) | Replace all placeholder in an SQL string |
