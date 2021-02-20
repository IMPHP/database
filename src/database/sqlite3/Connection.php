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
use im\database\BaseConnection;
use im\database\Result as BaseResult;
use im\database\Stmt as BaseStmt;
use SQLite3;
use Exception;

/**
 * Connection class for `SQLite` databases
 */
class Connection extends BaseConnection {

    /** @internal */
    protected ?SQLite3 $mDatabase;

    /** @internal */
    protected bool $mCountQueries;

    /** @internal */
    protected bool $mIsTransacting = false;

    /**
     * Open/Create SQLite database file
     *
     * @param $file
     *      Path to the SQLite database file or nothing for memory db
     *
     * @param $queryCount
     *      SQLite does not return any information about the amount of information
     *      is provided when doing a query. Setting this to 'true' will enable a hack that
     *      provides this information, but it comes at a cost. Each query will add an additional
     *      counting query, to extract that information.
     */
    public function __construct(string $file = null, bool $queryCount = false) {
        if (empty($file)) {
            $file = ":memory:";
        }

        try {
            /*
             * Looks strange, considering that exceptions is turned on in the second line.
             * But, sqlite will always throw exception in the constructor, just not in much else.
             */
            $sqlite = new SQLite3($file, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
            $sqlite->enableExceptions(true);
            $sqlite->busytimeout(20000);

        } catch (Exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        $this->mCountQueries = $queryCount;
        $this->mDatabase = $sqlite;
    }

    /**
     * @internal
     */
    public function getSQLite(): SQLite3 {
        return $this->mDatabase;
    }

    /**
     * @inheritDoc
     */
    #[Override("im\database\BaseConnection")]
    public function beginTransaction(): bool {
        if (!$this->mIsTransacting) {
            try {
                $this->mIsTransacting = $this->mDatabase->exec("BEGIN");

            } catch (Exception $e) {}
        }

        return $this->mIsTransacting;
    }

    /**
     * @inheritDoc
     */
    #[Override("im\database\BaseConnection")]
    public function rollbackTransaction(): bool {
        if ($this->mIsTransacting) {
            try {
                $this->mIsTransacting = !$this->mDatabase->exec("ROLLBACK");

            } catch (Exception $e) {}
        }

        return !$this->mIsTransacting;
    }

    /**
     * @inheritDoc
     */
    #[Override("im\database\BaseConnection")]
    public function commitTransaction(): bool {
        if ($this->mIsTransacting) {
            try {
                $this->mIsTransacting = !$this->mDatabase->exec("COMMIT");

            } catch (Exception $e) {}
        }

        return !$this->mIsTransacting;
    }

    /**
     * @inheritDoc
     */
    #[Override("im\database\BaseConnection")]
    public function escape(mixed $data): string {
        if (is_string($data)) {
            try {
                return "'".$this->mDatabase->escapeString($data)."'";

            } catch (Exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return parent::escape($data);
    }

    /**
     * @inheritDoc
     */
    #[Override("im\database\BaseConnection")]
    public function stmt(string $sql): BaseStmt {
        return new Stmt($this, $sql, $this->mCountQueries);
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function enquire(string $sql, mixed ...$data): ?BaseResult {
        $sql = trim($sql);
        $action = strtolower(substr($sql, 0, strpos($sql, ' ')));
        $numRows = -1;

        if (count($data) > 0) {
            $sql = $this->embedSQLValues($sql, ...$data);
        }

        if ($this->mCountQueries && $action == "select") {
            try {
                /*
                 * Work-around for SQLite's missing number of rows feature
                 */
                $result = $this->mDatabase->query("SELECT COUNT(*) FROM ($sql) as innerCount");
                $numRows = $result->fetcharray(SQLITE3_NUM)[0];
                $result->finalize();

            } catch (Exception $e) {}
        }

        try {
            $result = $this->mDatabase->query($sql);

            /*
             * This is to combat Bug: #64531
             *
             * Whenever that result set is accessed, the statement
             * that produced it, will be executed a second time. NOT good on statements
             * that make changes to the database. Now, such statements should not be executed
             * from this method, but it might and it should not produce issues.
             *
             * Also, these types of statements should not produce result sets to begin with,
             * and that is the way IMDB works, so this is also a mater of consistency.
             */
            if ($result->numColumns() == 0) {
                $result = null;
            }

        } catch (Exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        return is_object($result) ? new Result($result, $numRows) : null;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function execute(string $sql, mixed ...$data): int {
        if (count($data) > 0) {
            $sql = $this->embedSQLValues($sql, ...$data);
        }

        try {
            $this->mDatabase->exec($sql);
            $numRows = $this->mDatabase->changes();

        } catch (Exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        return $numRows;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function isConnected(): bool {
        return $this->mDatabase != null;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function driver(): string {
        return "sqlite3";
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function platform(): string {
        return "SQLite";
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function close(): void {
        if ($this->mDatabase != null) {
            try {
                $this->mDatabase->close();

            } catch (Exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);

            } finally {
                $this->mDatabase = null;
            }
        }
    }
}
