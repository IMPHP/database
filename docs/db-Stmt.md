# [Database](db.md) / Stmt
 > im\database\Stmt
____

## Description
Defines a class used for prepared statements

## Synopsis
```php
interface Stmt {

    // Methods
    close(): void
    execute(mixed &...$data): int
    enquire(mixed &...$data): null|im\database\Result
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Stmt&nbsp;::&nbsp;close__](db-Stmt-close.md) | Close the prepared statement |
| [__Stmt&nbsp;::&nbsp;execute__](db-Stmt-execute.md) | Execute a statement on the database |
| [__Stmt&nbsp;::&nbsp;enquire__](db-Stmt-enquire.md) | Make an enquiry on the database |
