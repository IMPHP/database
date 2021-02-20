# [Database](db.md) / BaseConnection
 > im\database\BaseConnection
____

## Description
Base Connection that drivers can extend from.

 > The `escape()` method in this class uses a generic escape for `string` types. Depending on the database and/or it's character encoding, this may NOT provide a full-proff SQL safeguard. Any driver should replace this method and at the very least, deal with `string` types in a database/encoding specific way.  

## Synopsis
```php
abstract class BaseConnection implements im\database\Connection {

    // Methods
    public escape(mixed $data): string
    public embedSQLValues(string $sql, mixed &...$values): string

    // Inherited Methods
    abstract public beginTransaction(): bool
    abstract public rollbackTransaction(): bool
    abstract public commitTransaction(): bool
    abstract public driver(): string
    abstract public platform(): string
    abstract public close(): void
    abstract public isConnected(): bool
    abstract public stmt(string $sql): im\database\Stmt
    abstract public execute(string $sql, mixed ...$data): int
    abstract public enquire(string $sql, mixed ...$data): null|im\database\Result
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__BaseConnection&nbsp;::&nbsp;escape__](db-BaseConnection-escape.md) | Escape a user input value to make it SQL highjack safe  This method converts data types to the appropriate string representations |
| [__BaseConnection&nbsp;::&nbsp;embedSQLValues__](db-BaseConnection-embedSQLValues.md) | Replace all placeholder in an SQL string |
| [__BaseConnection&nbsp;::&nbsp;beginTransaction__](db-BaseConnection-beginTransaction.md) | Begin a transaction |
| [__BaseConnection&nbsp;::&nbsp;rollbackTransaction__](db-BaseConnection-rollbackTransaction.md) | Rollback any changes made after transactions was started |
| [__BaseConnection&nbsp;::&nbsp;commitTransaction__](db-BaseConnection-commitTransaction.md) | Commit any changes made after transactions was started |
| [__BaseConnection&nbsp;::&nbsp;driver__](db-BaseConnection-driver.md) | This return the name of this driver |
| [__BaseConnection&nbsp;::&nbsp;platform__](db-BaseConnection-platform.md) | Get the name of the platform _(database type)_ |
| [__BaseConnection&nbsp;::&nbsp;close__](db-BaseConnection-close.md) | Close the database connection |
| [__BaseConnection&nbsp;::&nbsp;isConnected__](db-BaseConnection-isConnected.md) | Check whether we have a connection to the database or not |
| [__BaseConnection&nbsp;::&nbsp;stmt__](db-BaseConnection-stmt.md) | Prepares and returns a prepared statement |
| [__BaseConnection&nbsp;::&nbsp;execute__](db-BaseConnection-execute.md) | Execute a statement on the database  Like `enquire()` only this will not return any result |
| [__BaseConnection&nbsp;::&nbsp;enquire__](db-BaseConnection-enquire.md) | Make an enquiry on the database  The $sql argument can be formated with placeholders |
