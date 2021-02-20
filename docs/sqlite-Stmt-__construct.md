# [SQLite3](sqlite.md) / [Stmt](sqlite-Stmt.md) :: __construct
 > im\database\sqlite3\Stmt
____

## Description
Creates a new prepared statement.

## Synopsis
```php
public __construct(im\database\sqlite3\Connection $conn, string $sql, bool $queryCount = FALSE)
```

## Parameters
| Name | Description |
| :--- | :---------- |
| conn | An `SQLite` connection instance |
| sql | SQL to execute on the prepared statement |
| queryCount | SQLite does not return any information about the amount of information<br />is provided when doing a query. Setting this to 'true' will enable a hack that<br />provides this information, but it comes at a cost. Each query will add an additional<br />counting query, to extract that information. |
