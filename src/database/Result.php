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

use im\util\Map;
use im\util\Vector;

/**
 * Defines a basic result class
 */
interface Result {

    /**
     * Moves the internal row pointer to a requested position
     *
     * @param $pos
     *      The new pointer position. Can also be negative to start from right to left
     *
     * @return
     *      Returns 'false' if position is out of range
     *
     * @throws im\exc\DBException
     *      Exception on error (Not including out of range position)
     */
    function seek(int $pos): bool;

    /**
     * Returns the number rows returned from the query.
     *
     * @return
     *      The number of rows
     */
    function length(): int;

    /**
     * Check to see if the result set is empty.
     *
     * Some databases may use more resources when it needs to
     * provide exact information about the row count in a result.
     * In cases where you only need to check to see if there is any data,
     * this method will be much faster on such databases.
     *
     * @return
     *      This will return 'true' on `0` rows and 'false' on rows `>= 1`.
     */
    function empty(): bool;

    /**
     * Free up the result
     *
     * @param $cache
     *      Caches the result before cleaning up the attachment
     *      to the database, allowing the result data to continue to be available.
     *
     *      Note: This may use a lot of memory, depending on the data size
     */
    function destroy(bool $cache=false): void;

    /**
     * Fetch row as an assoc array and move pointer to the next
     *
     * @param $destroy
     *      If true, return the curren row and destroy the result
     *
     * @return
     *      Returns `null` on eof
     */
    function fetchAssoc(bool $destroy=false): ?Map;

    /**
     * Fetch row as an indexed array and move pointer to the next
     *
     * @param $destroy
     *      If true, return the current row and destroy the result
     *
     * @return
     *      Returns `null` on eof
     */
    function fetchRow(bool $destroy=false): ?Vector;

    /**
     * Fetch column in the current row and move pointer to the next
     *
     * @param $key
     *      Indexed or assoc row key. If this is 'NULL', the first column value is returned.
     *
     * @param $destroy
     *      If true, return column and destroy the result
     *
     * @return
     *      Returns `null` on eof
     */
    function fetchColumn(int|string $key=0, bool $destroy=false): mixed;
}
