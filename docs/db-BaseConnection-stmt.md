# [Database](db.md) / [BaseConnection](db-BaseConnection.md) :: stmt
 > im\database\BaseConnection
____

## Description
Prepares and returns a prepared statement

__Placeholders__:

| Chars | Type    |
| :---- | :------ |
| %s    | string  |
| %i    | integer |
| %d    | double  |
| %b    | blob    |

## Synopsis
```php
abstract public stmt(string $sql): im\database\Stmt
```
