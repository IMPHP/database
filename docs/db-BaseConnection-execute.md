# [Database](db.md) / [BaseConnection](db-BaseConnection.md) :: execute
 > im\database\BaseConnection
____

## Description
Execute a statement on the database

Like `enquire()` only this will not return any result.
The $sql argument can be formated with placeholders.

__Placeholders__:

| Chars | Type    |
| :---- | :------ |
| %s    | string  |
| %i    | integer |
| %d    | double  |

Data arguments are properly dealed with and escaped, before being embeded to the SQL.
To deal with blob data, use a prepared statement.

## Synopsis
```php
abstract public execute(string $sql, mixed ...$data): int
```

## Parameters
| Name | Description |
| :--- | :---------- |
| sql | The SQL string including optional formatting |
| data | Data for the $sql placeholders |

## Return
Number of affected rows or -1 on error
