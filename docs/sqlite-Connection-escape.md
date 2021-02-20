# [SQLite3](sqlite.md) / [Connection](sqlite-Connection.md) :: escape
 > im\database\sqlite3\Connection
____

## Description
Escape a user input value to make it SQL highjack safe

This method converts data types to the appropriate string representations.
It also excludes types that does not go directly into SQL Queries.

| Type     | Description                                       |
| :------- | :------------------------------------------------ |
| int      | Converted to string representation of their value |
| boolean  | Converted into 1 or 0 for true and false          |
| string   | Properly escaped and wrapped around single quotes |
| NULL     | Converted to string representation of NULL        |
|          | Everything else is set to escaped string or NULL  |

 > Only use this if you are inserting data elements directory into your SQL Query. Do not use it on data elements added to the input parameters, which is what you should be using in most cases.  

## Synopsis
```php
public escape(mixed $data): string
```

## Parameters
| Name | Description |
| :--- | :---------- |
| data | The value to escape |

## Return
A safe escaped string where data type has been properly converted
