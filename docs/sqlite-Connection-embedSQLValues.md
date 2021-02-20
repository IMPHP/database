# [SQLite3](sqlite.md) / [Connection](sqlite-Connection.md) :: embedSQLValues
 > im\database\sqlite3\Connection
____

## Description
Replace all placeholder in an SQL string.

This method will replace all the placeholder with the values from
`$values`. While doing so, data will be converted into appropriate types and
`string` values will be excaped by the `escape()` method.

__Placeholders__:

| Chars | Type    |
| :---- | :------ |
| %s    | string  |
| %i    | integer |
| %d    | double  |

 > This is automatically done in both `execute()` and `enquire()`, so there is no need to use this method, unless you want to use the output SQL for something else.  

## Synopsis
```php
public embedSQLValues(string $sql, mixed &...$values): string
```

## Parameters
| Name | Description |
| :--- | :---------- |
| sql | The SQL string including optional formatting |
| values | Data for the $sql placeholders |

## Return
SQL string with replaced placeholders.
