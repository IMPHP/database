# [Database](db.md) / [Result](db-Result.md) :: seek
 > im\database\Result
____

## Description
Moves the internal row pointer to a requested position

## Synopsis
```php
seek(int $pos): bool
```

## Parameters
| Name | Description |
| :--- | :---------- |
| pos | The new pointer position. Can also be negative to start from right to left |

## Return
Returns 'false' if position is out of range
