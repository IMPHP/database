# [Database](db.md) / [BaseResult](db-BaseResult.md) :: destroy
 > im\database\BaseResult
____

## Description
Free up the result

## Synopsis
```php
abstract public destroy(bool $cache = FALSE): void
```

## Parameters
| Name | Description |
| :--- | :---------- |
| cache | Caches the result before cleaning up the attachment<br />to the database, allowing the result data to continue to be available.<br /><br />Note: This may use a lot of memory, depending on the data size |
