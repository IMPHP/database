# [Database](db.md) / [Connection](db-Connection.md) :: enquire
 > im\database\Connection
____

## Description
Make an enquiry on the database

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
enquire(string $sql, mixed ...$data): null|im\database\Result
```

## Parameters
| Name | Description |
| :--- | :---------- |
| sql | The SQL string including optional formatting |
| data | Data for the $sql placeholders |

## Return
A Result object containing the returned data
