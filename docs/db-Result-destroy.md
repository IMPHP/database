# [Database](db.md) / [Result](db-Result.md) :: destroy
 > im\database\Result
____

## Description
Free up the result

## Synopsis
```php
destroy(bool $cache = FALSE): void
```

## Parameters
| Name | Description |
| :--- | :---------- |
| cache | Caches the result before cleaning up the attachment<br />to the database, allowing the result data to continue to be available.<br /><br />Note: This may use a lot of memory, depending on the data size |
