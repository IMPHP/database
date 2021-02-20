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
use mysqli_result;
use mysqli_sql_exception;

/**
 * Result class for `MySQL` databases.
 */
class Result extends BaseResult {

    /** @internal  */
    protected ?mysqli_result $mResult;

    /** @internal  */
    protected int $mCursor = 0;

    /** @internal  */
    protected array $mCache = [];

    /** @internal  */
    protected int $mNumRows = 0;

    /**
     * @param $result
     *      An actual `MySQL` result 
     */
    public function __construct(mysqli_result $result) {
        $this->mResult = $result;
        $this->mNumRows = $result->num_rows;
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
            if ($this->mResult != null) {
                try {
                    $this->mResult->data_seek(0);

                    while ($row = $this->mResult->fetch_array(MYSQLI_BOTH)) {
                        $this->mCache[] = $row;
                    }

                } catch (mysqli_sql_exception $e) {
                    // Try to restore on error
                    try {
                        $this->mResult->data_seek($this->mCursor);

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

        if ($this->mResult != null) {
            try {
                $this->mResult->free();

            } catch (mysqli_sql_exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);

            } finally {
                $this->mResult = null;
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
                $this->mResult->data_seek($pos);

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
        if ($this->mResult != null) {
            try {
                $row = $this->mResult->fetch_assoc();

                if ($row != null) {
                    $this->mCursor++;
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
        if ($this->mResult != null) {
            try {
                $row = $this->mResult->fetch_row();

                if ($row != null) {
                    $this->mCursor++;
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
