# [SQLite3](sqlite.md) / [Connection](sqlite-Connection.md) :: __construct
 > im\database\sqlite3\Connection
____

## Description
Open/Create SQLite database file

## Synopsis
```php
public __construct(null|string $file = NULL, bool $queryCount = FALSE)
```

## Parameters
| Name | Description |
| :--- | :---------- |
| file | Path to the SQLite database file or nothing for memory db |
| queryCount | SQLite does not return any information about the amount of information<br />is provided when doing a query. Setting this to 'true' will enable a hack that<br />provides this information, but it comes at a cost. Each query will add an additional<br />counting query, to extract that information. |
