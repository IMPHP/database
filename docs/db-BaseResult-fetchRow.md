# [Database](db.md) / [BaseResult](db-BaseResult.md) :: fetchRow
 > im\database\BaseResult
____

## Description
Fetch row as an indexed array and move pointer to the next

## Synopsis
```php
abstract public fetchRow(bool $destroy = FALSE): null|im\util\Vector
```

## Parameters
| Name | Description |
| :--- | :---------- |
| destroy | If true, return the current row and destroy the result |

## Return
Returns `null` on eof
