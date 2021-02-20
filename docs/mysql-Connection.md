# [Database](db.md) / [MySQLi](mysql.md) / Connection
 > im\database\mysqli\Connection
____

## Description
Connection class for `MySQL` databases

## Synopsis
```php
class Connection extends im\database\BaseConnection implements im\database\Connection {

    // Methods
    public __construct(string $host, null|string $database = NULL, null|string $user = NULL, null|string $passwd = NULL, int $port = 3306)
    public setDatabase(string $database): void
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
| [__Connection&nbsp;::&nbsp;\_\_construct__](mysql-Connection-__construct.md) | Create a new `MySQL` connection |
| [__Connection&nbsp;::&nbsp;setDatabase__](mysql-Connection-setDatabase.md) | Set the database to be used with this connection |
| [__Connection&nbsp;::&nbsp;beginTransaction__](mysql-Connection-beginTransaction.md) | Begin a transaction |
| [__Connection&nbsp;::&nbsp;rollbackTransaction__](mysql-Connection-rollbackTransaction.md) | Rollback any changes made after transactions was started |
| [__Connection&nbsp;::&nbsp;commitTransaction__](mysql-Connection-commitTransaction.md) | Commit any changes made after transactions was started |
| [__Connection&nbsp;::&nbsp;escape__](mysql-Connection-escape.md) | Escape a user input value to make it SQL highjack safe  This method converts data types to the appropriate string representations |
| [__Connection&nbsp;::&nbsp;stmt__](mysql-Connection-stmt.md) | Prepares and returns a prepared statement |
| [__Connection&nbsp;::&nbsp;enquire__](mysql-Connection-enquire.md) |  |
| [__Connection&nbsp;::&nbsp;execute__](mysql-Connection-execute.md) |  |
| [__Connection&nbsp;::&nbsp;isConnected__](mysql-Connection-isConnected.md) |  |
| [__Connection&nbsp;::&nbsp;driver__](mysql-Connection-driver.md) |  |
| [__Connection&nbsp;::&nbsp;platform__](mysql-Connection-platform.md) |  |
| [__Connection&nbsp;::&nbsp;close__](mysql-Connection-close.md) |  |
| [__Connection&nbsp;::&nbsp;embedSQLValues__](mysql-Connection-embedSQLValues.md) | Replace all placeholder in an SQL string |
