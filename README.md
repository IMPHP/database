# IMPHP - Database
___

This library provides a very light weight database abstraction layer, that creates consistency and ease across multiple databases. It's very easy and simple to use, unlike many of the PHP provided database drivers and what it supports on one database, it supports on all the supported databases.

__Errors__  
Some database libraries prints warnings, some throws exceptions, deals with `last error` methods and so on. Some even mixes it by having parts of their library do one thing and other parts do something else.

This library simplifies this by always dealing with database errors using exceptions. There is no exception to that rule. If you wrap your database connectivity in a `try/catch`, you can be sure that your code will stop at the first error it encounters, without having to deal with warnings, checking error codes after each method call and so on. Every single return value on any method, is a successful return.

### Full Documentation

You can view the [Full Documentation](docs/db.md) to lean more.

### Installation

__Using .phar library__

```sh
wget https://github.com/IMPHP/database/releases/download/<version>/imphp-database.phar
```

```php
require "imphp-database.phar";

...
```

__Clone via git__

```sh
git clone https://github.com/IMPHP/database.git imphp/database/
```

__Composer _(Packagist)___

```sh
composer require imphp/database
```

### Usage
----
Each driver can be found in their own namespace such as `im/database/mysqli` and `im/database/sqlite3`. Each driver has it's own `Connection` class based on `im/database/Connection` that is used to establish connection to the database and utilize it. A query will return an instance of `im/database/Result` that can be used to access the requested data.

```php
use im\database\mysqli\Connection;

$id = 10;
$conn = new Connection("localhost", "My_DB", "user", "MyPassw");
$result = $conn->enquire("SELECT FROM tbl WHERE id=%i", $id);
$rownum = $result->fetchColumn("row_num", true);

$affected = $conn->execute("DELETE FROM tbl WHERE row_num=%i", $rownum);

if ($affected > 0) {
    echo "Success!";
}

$conn->close();
```

__Prepared Statements__

```php
use im\database\sqlite3\Connection;

$conn = new Connection("My_DB.db");
$stmt = $conn->stmt("UPDATE tbl SET role=%s WHERE id=%i");
$users = [
    10 => "admin",
    12 => "user",
    25 => "admin"
];

foreach ($users as $id => $role) {
    $stmt->execute($role, $id);
}

$stmt->close();
$conn->close();
```
