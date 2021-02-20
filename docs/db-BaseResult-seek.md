# [Database](db.md) / [BaseResult](db-BaseResult.md) :: seek
 > im\database\BaseResult
____

## Description
Moves the internal row pointer to a requested position

## Synopsis
```php
abstract public seek(int $pos): bool
```

## Parameters
| Name | Description |
| :--- | :---------- |
| pos | The new pointer position. Can also be negative to start from right to left |

## Return
Returns 'false' if position is out of range
