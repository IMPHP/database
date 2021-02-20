# [Database](db.md) / [Stmt](db-Stmt.md) :: execute
 > im\database\Stmt
____

## Description
Execute a statement on the database.
Like `enquire()` only this will not return any result.

The $sql argument can be formated with plaveholders.

__Placeholders__:

| Chars | Type    |
| :---- | :------ |
| %s    | string  |
| %i    | integer |
| %d    | double  |
| %b    | blob    |

$data arguments are bound to the prepared statement.

## Synopsis
```php
execute(mixed &...$data): int
```

## Parameters
| Name | Description |
| :--- | :---------- |
| data | Data for the $sql placeholders |

## Return
Number of affected rows or -1 on error
