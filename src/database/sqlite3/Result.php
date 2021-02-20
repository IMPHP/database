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

namespace im\database\sqlite3;

use im\exc\DBException;
use im\util\Map;
use im\util\Vector;
use im\database\BaseResult;
use SQLite3Result;
use Exception;

/**
 * Result class for `SQLite` databases
 */
class Result extends BaseResult {

    /**  @internal */
    protected ?SQLite3Result $mResult;

    /**  @internal */
    protected int $mNumRows;

    /**  @internal */
    protected int $mCursor = 0;

    /**  @internal */
    protected array $mCache = [];

    /**  @internal */
    protected bool $mEmpty = true;

    /**
     * Creates a result instance for an `SQLite` query.
     *
     * @param $result
     *      The actual `SQLite` result
     *
     * @param $numRows
     *      Optional number of rows. 
     */
    public function __construct(SQLite3Result $result, int $numRows=-1) {
        $this->mResult = $result;
        $this->mNumRows = $numRows;

        try {
            if ($numRows < 0 && $this->mResult->fetchArray()) {
                $this->mResult->reset();
                $this->mEmpty = false;

            } else {
                $this->mEmpty = !($numRows > 0);
            }

        } catch (Exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function length(): int {
        if ($this->mNumRows < 0) {
            if ($this->mResult == null) {
                $this->mNumRows = count($this->mCache);

            } else {
                try {
                    $this->mResult->reset();
                    $this->mNumRows = 0;

                    while($this->mResult->fetchArray()) {
                        $this->mNumRows++;
                    }

                } catch (Exception $e) {
                    throw new DBException($e->getMessage(), $e->getCode(), $e);
                }

                $this->seek($this->mCursor);
            }
        }

        return $this->mNumRows;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function empty(): bool {
        return ($this->mNumRows < 0 && $this->mEmpty)
                    || $this->mNumRows == 0;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function seek(int $pos): bool {
        if ($pos < 0) {
            $numRows = $this->length();
            $pos = $numRows + $pos;

            if ($pos < 0) {
                return false;
            }
        }

        if ($this->mResult != null) {
            if (!$this->internal_seek($pos)) {
                $this->internal_seek($this->mCursor);

            } else {
                $this->mCursor = $pos;
            }

        } else {
            // Cache position
            if ($pos >= $this->length()) {
                return false;
            }

            $this->mCursor = $pos;
        }

        return true;
    }

    /**
     * @internal
     */
    private function internal_seek(int $pos): bool {
        try {
            /*
             * Sqlite does not have seek, so we obtain this feature a bit differently.
             * Since SQLite does not support row count, and the work-arounds are a bit slow,
             * depending on which one is active and the amount of data, we try to avoid it when possible.
             * Instead we will just reset the result set and try to loop down to the requested position,
             * rather than checking the position against the row count.
             */
            $this->mResult->reset();
            $cursor = 0;

            for (; $cursor < $pos; $cursor++) {
                if ($this->mResult->fetcharray(SQLITE3_NUM) == false) {
                    return false;
                }
            }

            return true;

        } catch (Exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function destroy(bool $cache = false): void {
        if ($cache) {
            if ($this->mResult != null) {
                try {
                    $this->mResult->reset();

                    while ($row = $this->mResult->fetcharray(SQLITE3_BOTH)) {
                        $this->mCache[] = $row;
                    }

                } catch (Exception $e) {
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
                $this->mResult->finalize();

            } catch (Exception $e) {
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
    public function fetchAssoc(bool $destroy=false): ?Map {
        if ($this->mResult != null) {
            try {
                $row = $this->mResult->fetcharray(SQLITE3_ASSOC);

            } catch (Exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);
            }

        } else if ($this->mCursor < $this->length()) {
            $row = $this->mCache[$this->mCursor++] ?? null;

            if ($row != null) {
                $row = array_filter($row, 'is_string', ARRAY_FILTER_USE_KEY);
            }
        }

        if ($destroy) {
            $this->destroy();
        }

        if (isset($row) && is_array($row)) {
            return new Map($row);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseResult")]
    public function fetchRow(bool $destroy=false): ?Vector {
        if ($this->mResult != null) {
            try {
                $row = $this->mResult->fetcharray(SQLITE3_NUM);

            } catch (Exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);
            }

        } else if ($this->mCursor < $this->length()) {
            $row = $this->mCache[$this->mCursor++] ?? null;

            if ($row != null) {
                $row = array_filter($row, 'is_int', ARRAY_FILTER_USE_KEY);
            }
        }

        if ($destroy) {
            $this->destroy();
        }

        if (isset($row) && is_array($row)) {
            return Vector($row);
        }

        return null;
    }
}
