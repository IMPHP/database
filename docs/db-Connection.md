# [Database](db.md) / Connection
 > im\database\Connection
____

## Description
Defines a basic connection class

## Synopsis
```php
interface Connection {

    // Methods
    beginTransaction(): bool
    rollbackTransaction(): bool
    commitTransaction(): bool
    escape(mixed $data): string
    driver(): string
    platform(): string
    close(): void
    isConnected(): bool
    stmt(string $sql): im\database\Stmt
    execute(string $sql, mixed ...$data): int
    enquire(string $sql, mixed ...$data): null|im\database\Result
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Connection&nbsp;::&nbsp;beginTransaction__](db-Connection-beginTransaction.md) | Begin a transaction |
| [__Connection&nbsp;::&nbsp;rollbackTransaction__](db-Connection-rollbackTransaction.md) | Rollback any changes made after transactions was started |
| [__Connection&nbsp;::&nbsp;commitTransaction__](db-Connection-commitTransaction.md) | Commit any changes made after transactions was started |
| [__Connection&nbsp;::&nbsp;escape__](db-Connection-escape.md) | Escape a user input value to make it SQL highjack safe  This method converts data types to the appropriate string representations |
| [__Connection&nbsp;::&nbsp;driver__](db-Connection-driver.md) | This return the name of this driver |
| [__Connection&nbsp;::&nbsp;platform__](db-Connection-platform.md) | Get the name of the platform _(database type)_ |
| [__Connection&nbsp;::&nbsp;close__](db-Connection-close.md) | Close the database connection |
| [__Connection&nbsp;::&nbsp;isConnected__](db-Connection-isConnected.md) | Check whether we have a connection to the database or not |
| [__Connection&nbsp;::&nbsp;stmt__](db-Connection-stmt.md) | Prepares and returns a prepared statement |
| [__Connection&nbsp;::&nbsp;execute__](db-Connection-execute.md) | Execute a statement on the database  Like `enquire()` only this will not return any result |
| [__Connection&nbsp;::&nbsp;enquire__](db-Connection-enquire.md) | Make an enquiry on the database  The $sql argument can be formated with placeholders |
