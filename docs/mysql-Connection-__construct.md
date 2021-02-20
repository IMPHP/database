# [MySQLi](mysql.md) / [Connection](mysql-Connection.md) :: __construct
 > im\database\mysqli\Connection
____

## Description
Create a new `MySQL` connection

 > Use '127.0.0.1' instead of 'localhost' if you are on Windows >= 7.  

## Synopsis
```php
public __construct(string $host, null|string $database = NULL, null|string $user = NULL, null|string $passwd = NULL, int $port = 3306)
```

## Parameters
| Name | Description |
| :--- | :---------- |
| host | Host address for the database server |
| database | Optional name of the database |
| user | Optional user name |
| passwd | Optional user password |
| port | Optional server port, defaults to '3306' |
