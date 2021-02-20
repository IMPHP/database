# [Database](db.md) / [Stmt](db-Stmt.md) :: enquire
 > im\database\Stmt
____

## Description
Make an enquiry on the database

 >      If you run statements such as `INSERT`, `DELETE`      and so on, you `execute()` instead.<br /><br />The $sql argument can be formated with plaveholders.<br /><br />__Placeholders__:<br /><br />| Chars | Type    | | :---- | :------ | | %s    | string  | | %i    | integer | | %d    | double  | | %b    | blob    |<br /><br />$data arguments are bound to the prepared statement.  

## Synopsis
```php
enquire(mixed &...$data): null|im\database\Result
```

## Parameters
| Name | Description |
| :--- | :---------- |
| data | Data for the $sql placeholders |

## Return
A Result object containing the returned data or
`NULL` on error or if the statement did not produce a result. 
