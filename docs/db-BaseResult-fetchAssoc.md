# [Database](db.md) / [BaseResult](db-BaseResult.md) :: fetchAssoc
 > im\database\BaseResult
____

## Description
Fetch row as an assoc array and move pointer to the next

## Synopsis
```php
abstract public fetchAssoc(bool $destroy = FALSE): null|im\util\Map
```

## Parameters
| Name | Description |
| :--- | :---------- |
| destroy | If true, return the curren row and destroy the result |

## Return
Returns `null` on eof
