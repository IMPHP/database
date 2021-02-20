# [Database](db.md) / [Result](db-Result.md) :: fetchRow
 > im\database\Result
____

## Description
Fetch row as an indexed array and move pointer to the next

## Synopsis
```php
fetchRow(bool $destroy = FALSE): null|im\util\Vector
```

## Parameters
| Name | Description |
| :--- | :---------- |
| destroy | If true, return the current row and destroy the result |

## Return
Returns `null` on eof
