# [Database](db.md) / BaseResult
 > im\database\BaseResult
____

## Description
Base result class that drivers can extend from. 

## Synopsis
```php
abstract class BaseResult implements im\database\Result {

    // Methods
    public fetchColumn(string|int $key = 0, bool $destroy = FALSE): mixed

    // Inherited Methods
    abstract public seek(int $pos): bool
    abstract public length(): int
    abstract public empty(): bool
    abstract public destroy(bool $cache = FALSE): void
    abstract public fetchAssoc(bool $destroy = FALSE): null|im\util\Map
    abstract public fetchRow(bool $destroy = FALSE): null|im\util\Vector
}
```

## Methods
| Name | Description |
| :--- | :---------- |
| [__BaseResult&nbsp;::&nbsp;fetchColumn__](db-BaseResult-fetchColumn.md) | Fetch column in the current row and move pointer to the next |
| [__BaseResult&nbsp;::&nbsp;seek__](db-BaseResult-seek.md) | Moves the internal row pointer to a requested position |
| [__BaseResult&nbsp;::&nbsp;length__](db-BaseResult-length.md) | Returns the number rows returned from the query |
| [__BaseResult&nbsp;::&nbsp;empty__](db-BaseResult-empty.md) | Check to see if the result set is empty |
| [__BaseResult&nbsp;::&nbsp;destroy__](db-BaseResult-destroy.md) | Free up the result |
| [__BaseResult&nbsp;::&nbsp;fetchAssoc__](db-BaseResult-fetchAssoc.md) | Fetch row as an assoc array and move pointer to the next |
| [__BaseResult&nbsp;::&nbsp;fetchRow__](db-BaseResult-fetchRow.md) | Fetch row as an indexed array and move pointer to the next |
