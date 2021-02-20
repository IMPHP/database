# [Database](db.md) / Result
 > im\database\Result
____

## Description
Defines a basic result class

## Synopsis
```php
interface Result {

    // Methods
    seek(int $pos): bool
    length(): int
    empty(): bool
    destroy(bool $cache = FALSE): void
    fetchAssoc(bool $destroy = FALSE): null|im\util\Map
    fetchRow(bool $destroy = FALSE): null|im\util\Vector
    fetchColumn(string|int $key = 0, bool $destroy = FALSE): mixed
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__Result&nbsp;::&nbsp;seek__](db-Result-seek.md) | Moves the internal row pointer to a requested position |
| [__Result&nbsp;::&nbsp;length__](db-Result-length.md) | Returns the number rows returned from the query |
| [__Result&nbsp;::&nbsp;empty__](db-Result-empty.md) | Check to see if the result set is empty |
| [__Result&nbsp;::&nbsp;destroy__](db-Result-destroy.md) | Free up the result |
| [__Result&nbsp;::&nbsp;fetchAssoc__](db-Result-fetchAssoc.md) | Fetch row as an assoc array and move pointer to the next |
| [__Result&nbsp;::&nbsp;fetchRow__](db-Result-fetchRow.md) | Fetch row as an indexed array and move pointer to the next |
| [__Result&nbsp;::&nbsp;fetchColumn__](db-Result-fetchColumn.md) | Fetch column in the current row and move pointer to the next |
