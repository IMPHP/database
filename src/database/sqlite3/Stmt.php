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
use im\database\Stmt as BaseStmt;
use im\database\Result as BaseResult;
use im\util\Vector;
use SQLite3Stmt;
use Exception;

/**
 * Prepared Statements class for `SQLite` databases
 */
class Stmt implements BaseStmt {

    /** @internal */
    protected array $mBoundValues = [];

    /** @internal */
    protected ?Connection $mConnection = null;

    /** @internal */
    protected ?SQLite3Stmt $mStmt = null;

    /** @internal */
    protected ?SQLite3Stmt $mStmt_rowcount = null;

    /** @internal */
    protected array $mTypes = [
        "i" => SQLITE3_INTEGER,
        "f" => SQLITE3_FLOAT,
        "s" => SQLITE3_TEXT,
        "b" => SQLITE3_BLOB
    ];

    /**
     * Creates a new prepared statement.
     *
     * @param $conn
     *      An `SQLite` connection instance
     *
     * @param $sql
     *      SQL to execute on the prepared statement
     *
     * @param $queryCount
     *      SQLite does not return any information about the amount of information
     *      is provided when doing a query. Setting this to 'true' will enable a hack that
     *      provides this information, but it comes at a cost. Each query will add an additional
     *      counting query, to extract that information.
     */
    public function __construct(Connection $conn, string $sql, bool $queryCount = false) {
        $sql = trim($sql);
        $action = strtolower(substr($sql, 0, strpos($sql, ' ')));
        $params = new Vector();
        $sql = $conn->parseSQLParam($sql, $params);

        try {
            $stmt = $conn->getSQLite()->prepare($sql);

            if ($params->length() > 0) {
                foreach ($params as $key => $val) {
                    $this->mBoundValues[$key] = [
                        "value" => null,
                        "type" => $val
                    ];

                    $stmt->bindParam(
                        $key+1,
                        $this->mBoundValues[$key]["value"],
                        $this->mTypes[$val]
                    );
                }
            }

            $this->mStmt = $stmt;
            $this->mConnection = $conn;

        } catch (Exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        if ($queryCount && $action == "select") {
            /*
             * Try creating the row count work-around stmt.
             * However, do not crash if it fails, we do have other methods to use.
             */
            try {
                $stmt_count = $this->mDatabase->prepare("SELECT COUNT(*) FROM ($sql) as innerCount");

                if ($params->length() > 0) {
                    foreach ($params as $key => $val) {
                        $stmt_count->bindParam($key, $this->mBoundValues[$key]["value"]);
                    }
                }

                $this->mStmt_rowcount = $stmt_count;

            } catch (Exception $e) {}
        }
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\Stmt")]
    public function enquire(mixed &...$data): ?BaseResult {
        foreach ($data as $key => $value) {
            $this->mBoundValues[$key]["value"] = $value;
        }

        $numRows = -1;

        if ($this->mStmt_rowcount != null) {
            try {
                $this->mStmt_rowcount->execute();
                $numRows = $result->fetcharray(SQLITE3_NUM)[0];
                $result->finalize();

            } catch (Exception $e) {}
        }

        try {
            $result = $this->mStmt->execute();

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
    #[Override("im\database\Stmt")]
    public function execute(mixed &...$data): int {
        foreach ($data as $key => $value) {
            $this->mBoundValues[$key]["value"] = $value;
        }

        try {
            $database = $this->mConnection->getSQLite();
            $this->mStmt->execute();
            $numRows = $database->changes();

        } catch (Exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        return $numRows;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\Stmt")]
    public function close(): void {
        if ($this->mStmt != null) {
            $this->mStmt->close();
            $this->mStmt = null;
            $this->mDatabase = null;
            $this->mBoundValues = [];

            if ($this->mStmt_rowcount != null) {
                $this->mStmt_rowcount->close();
                $this->mStmt_rowcount = null;
            }
        }
    }
}
