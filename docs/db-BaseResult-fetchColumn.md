# [Database](db.md) / [BaseResult](db-BaseResult.md) :: fetchColumn
 > im\database\BaseResult
____

## Description
Fetch column in the current row and move pointer to the next

## Synopsis
```php
public fetchColumn(string|int $key = 0, bool $destroy = FALSE): mixed
```

## Parameters
| Name | Description |
| :--- | :---------- |
| key | Indexed or assoc row key. If this is 'NULL', the first column value is returned. |
| destroy | If true, return column and destroy the result |

## Return
Returns `null` on eof
