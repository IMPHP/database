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

 namespace im\database\mysqli;

use im\exc\DBException;
use im\database\BaseResult;
use im\util\Map;
use im\util\Vector;
use mysqli_stmt;
use mysqli_sql_exception;

/**
 * Result class for `MySQL` databases using STMT objects.
 *
 * @note
 *      The `mysqlnd` driver includes a 'get_result()' method in it's stmt object.
 *      It returns a full featured result object that can be used just as
 *      the one returned using the normal query method.
 */
class StmtResult extends BaseResult {

    /** @internal  */
    protected ?mysqli_stmt $mStmt;

    /** @internal  */
    protected array $mFetchedAssoc = [];

    /** @internal  */
    protected array $mFetchedRow = [];

    /** @internal  */
    protected int $mCursor = 0;

    /** @internal  */
    protected array $mCache = [];

    /** @internal  */
    protected int $mNumRows = 0;

    /**
     * @param $stmt
     *      An `MySQL` stmt object
     */
    public function __construct(mysqli_stmt $stmt) {
        try {
            $meta = $stmt->result_metadata();
            $bound = [];
            $i = 0;

            /*
             * Bind the fetch arrays so that we can retrive the content
             * in the fetch methods later.
             */
            while($field = $meta->fetch_field()) {
                $bound[$i] = &$this->mFetchedAssoc[$field->name];
                $this->mFetchedRow[$i] = &$this->mFetchedAssoc[$field->name];

                $i++;
            }

            if ($i > 0) {
                $stmt->bind_result(...$bound);
            }

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        $this->mStmt = $stmt;
        $this->mNumRows = $stmt->num_rows;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function length(): int {
        return $this->mNumRows;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function empty(): bool {
        return $this->mNumRows == 0;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function destroy(bool $cache = false): void {
        if ($cache) {
            if ($this->mStmt != null) {
                try {
                    $this->mStmt->data_seek(0);

                    while ($this->mStmt->fetch()) {
                        $row = [];

                        // DeReference the assoc array
                        foreach ($this->mFetchedAssoc as $key => $val) {
                            $row[$key] = $val;
                        }

                        // DeReference the indexed array
                        foreach ($this->mFetchedRow as $key => $val) {
                            $row[$key] = $val;
                        }

                        $this->mCache[] = $row;
                    }

                } catch (mysqli_sql_exception $e) {
                    // Try to restore on error
                    try {
                        $this->mStmt->data_seek(0);

                    } catch (mysqli_sql_exception $ignore) {} finally {
                        $this->mCache = [];
                    }

                    throw new DBException($e->getMessage(), $e->getCode(), $e);
                }
            }

        } else {
            $this->mCache = [];
            $this->mCursor = 0;
            $this->mNumRows = 0;
        }

        if ($this->mStmt != null) {
            try {
                $this->mStmt->free_result();

            } catch (mysqli_sql_exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);

            } finally {
                $this->mFetchedRow = [];
                $this->mFetchedAssoc = [];
                $this->mStmt = null;
            }
        }
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function seek(int $pos): bool {
        if ($pos < 0) {
            $pos = $this->mNumRows + $pos;

            if ($pos < 0) {
                return false;
            }
        }

        if ($pos >= $this->mNumRows) {
            return false;
        }

        if ($this->mResult != null) {
            try {
                $this->mStmt->data_seek($pos);

            } catch (mysqli_sql_exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);
            }
        }

        $this->mCursor = $pos;

        return true;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function fetchAssoc(bool $destroy=false): ?Map {
        if ($this->mStmt != null) {
            try {
                if ($this->mStmt->fetch()) {
                    $this->mCursor++;
                    $row = [];

                    // DeReference the array
                    foreach ($this->mFetchedAssoc as $key => $val) {
                        $row[$key] = $val;
                    }

                } else {
                    $row = null;
                }

            } catch (mysqli_sql_exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);
            }

        } else {
            $row = $this->mCache[$this->mCursor] ?? null;

            if ($row != null) {
                $row = array_filter($row, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->mCursor++;
            }
        }

        if ($destroy) {
            $this->destroy();
        }

        return $row != null ? new Map($row) : null;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function fetchRow(bool $destroy=false): ?Vector {
        if ($this->mStmt != null) {
            try {
                if ($this->mStmt->fetch()) {
                    $this->mCursor++;
                    $row = [];

                    // DeReference the array
                    foreach ($this->mFetchedRow as $val) {
                        $row[] = $val;
                    }

                } else {
                    $row = null;
                }

            } catch (mysqli_sql_exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);
            }

        } else {
            $row = $this->mCache[$this->mCursor] ?? null;

            if ($row != null) {
                $row = array_filter($row, 'is_int', ARRAY_FILTER_USE_KEY);
                $this->mCursor++;
            }
        }

        if ($destroy) {
            $this->destroy();
        }

        return $row != null ? new Vector($row) : null;
    }
}
