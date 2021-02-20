# [Database](db.md) / [Result](db-Result.md) :: empty
 > im\database\Result
____

## Description
Check to see if the result set is empty.

Some databases may use more resources when it needs to
provide exact information about the row count in a result.
In cases where you only need to check to see if there is any data,
this method will be much faster on such databases.

## Synopsis
```php
empty(): bool
```

## Return
This will return 'true' on `0` rows and 'false' on rows `>= 1`.
