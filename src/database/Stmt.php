<?php
/*
 * This file is part of the IMPHP Project: https://github.com/IMPHP
 *
 * Copyright (c) 2019 Daniel Bergløv, License: MIT
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace im\database;

/**
 * Defines a class used for prepared statements
 */
interface Stmt {

    /**
     * Close the prepared statement
     */
    function close(): void;

    /**
     * Execute a statement on the database.
     * Like `enquire()` only this will not return any result.
     *
     * The $sql argument can be formated with plaveholders.
     *
     * __Placeholders__:
     *
     * | Chars | Type    |
     * | :---- | :------ |
     * | %s    | string  |
     * | %i    | integer |
     * | %d    | double  |
     * | %b    | blob    |
     *
     * $data arguments are bound to the prepared statement.
     *
     * @param $data
     *      Data for the $sql placeholders
     *
     * @return int
     *      Number of affected rows or -1 on error
     */
    function execute(mixed &...$data): int;

    /**
     * Make an enquiry on the database
     *
     * @note
     *      If you run statements such as `INSERT`, `DELETE`
     *      and so on, you `execute()` instead.
     *
     * The $sql argument can be formated with plaveholders.
     *
     * __Placeholders__:
     *
     * | Chars | Type    |
     * | :---- | :------ |
     * | %s    | string  |
     * | %i    | integer |
     * | %d    | double  |
     * | %b    | blob    |
     *
     * $data arguments are bound to the prepared statement.
     *
     * @param $data
     *      Data for the $sql placeholders
     *
     * @return
     *      A Result object containing the returned data or
     *      `NULL` on error or if the statement did not produce a result. 
     */
    function enquire(mixed &...$data): ?Result;
}
