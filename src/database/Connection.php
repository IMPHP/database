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
 * Defines a basic connection class
 */
interface Connection {

    /**
     * Begin a transaction
     */
    function beginTransaction(): bool;

    /**
     * Rollback any changes made after transactions was started
     */
    function rollbackTransaction(): bool;

    /**
     * Commit any changes made after transactions was started
     */
    function commitTransaction(): bool;

    /**
     * Escape a user input value to make it SQL highjack safe
     *
     * This method converts data types to the appropriate string representations.
     * It also excludes types that does not go directly into SQL Queries.
     *
     * | Type     | Description                                       |
     * | :------- | :------------------------------------------------ |
     * | int      | Converted to string representation of their value |
     * | boolean  | Converted into 1 or 0 for true and false          |
     * | string   | Properly escaped and wrapped around single quotes |
     * | NULL     | Converted to string representation of NULL        |
     * |          | Everything else is set to escaped string or NULL  |
     *
     * @note
     *      Only use this if you are inserting data elements directory into your SQL Query.
     *      Do not use it on data elements added to the input parameters, which is what you should
     *      be using in most cases.
     *
     * @param $data
     *      The value to escape
     *
     * @return
     *      A safe escaped string where data type has been properly converted
     */
    function escape(mixed $data): string;

    /**
     * This return the name of this driver. For an example `mysqli`.
     *
     * @return
     *      The name of the database
     */
    function driver(): string;

    /**
     * Get the name of the platform _(database type)_.
     * This is the name of the database platform like `MySQL`.
     *
     * @return string
     *      The name of the database
     */
    function platform(): string;

    /**
     * Close the database connection
     */
    function close(): void;

    /**
     * Check whether we have a connection to the database or not
     *
     * @return
     *      True if the connection is alive, false otherwise
     */
    function isConnected(): bool;

    /**
     * Prepares and returns a prepared statement
     *
     * __Placeholders__:
     *
     * | Chars | Type    |
     * | :---- | :------ |
     * | %s    | string  |
     * | %i    | integer |
     * | %d    | double  |
     * | %b    | blob    |
     */
    function stmt(string $sql): Stmt;

    /**
     * Execute a statement on the database
     *
     * Like `enquire()` only this will not return any result.
     * The $sql argument can be formated with placeholders.
     *
     * __Placeholders__:
     *
     * | Chars | Type    |
     * | :---- | :------ |
     * | %s    | string  |
     * | %i    | integer |
     * | %d    | double  |
     *
     * Data arguments are properly dealed with and escaped, before being embeded to the SQL.
     * To deal with blob data, use a prepared statement.
     *
     * @param $sql
     *      The SQL string including optional formatting
     *
     * @param $data
     *      Data for the $sql placeholders
     *
     * @return
     *      Number of affected rows or -1 on error
     */
    function execute(string $sql, mixed ...$data): int;

    /**
     * Make an enquiry on the database
     *
     * The $sql argument can be formated with placeholders.
     *
     * __Placeholders__:
     *
     * | Chars | Type    |
     * | :---- | :------ |
     * | %s    | string  |
     * | %i    | integer |
     * | %d    | double  |
     *
     * Data arguments are properly dealed with and escaped, before being embeded to the SQL.
     * To deal with blob data, use a prepared statement.
     *
     * @param $sql
     *      The SQL string including optional formatting
     *
     * @param $data
     *      Data for the $sql placeholders
     *
     * @return
     *      A Result object containing the returned data
     */
    function enquire(string $sql, mixed ...$data): ?Result;
}
