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
use im\database\Stmt as BaseStmt;
use im\database\Result as BaseResult;
use im\util\Vector;
use mysqli_stmt;
use mysqli_sql_exception;

/**
 * Prepared Statements class for `MySQL` databases
 */
class Stmt implements BaseStmt {

    /** @ignore */
    protected ?mysqli_stmt $mStmt;

    /** @ignore */
    protected ?Connection $mConnection;

    /** @ignore */
    protected array $mBoundValues = [];

    /** @ignore */
    protected array $mTypes = [
        "i" => 'i',
        "f" => 'd',
        "s" => 's',
        "b" => 'b'
    ];

    /**
     * @param $conn
     *      A MySQLi connection
     *
     * @param $sql
     *      SQL statement to prepare
     */
    public function __construct(Connection $conn, string $sql) {
        try {
            $params = new Vector();
            $sql = $conn->parseSQLParam($sql, $params);
            $stmt = $conn->getMysqli()->prepare($sql);

            if ($params->length() > 0) {
                $bind = [];
                $types = "";

                foreach ($params as $key => $val) {
                    $this->mBoundValues[$key] = [
                        "value" => null,
                        "type" => $val
                    ];

                    $bind[] = &$this->mBoundValues[$key]["value"];
                    $types .= $this->mTypes[$val];
                }

                $stmt->bind_param($types, ...$bind);
            }

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        $this->mStmt = $stmt;
        $this->mConnection = $conn;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\Stmt")]
    public function enquire(mixed &...$data): ?BaseResult {
        try {
            foreach ($data as $key => $value) {
                $this->mBoundValues[$key]["value"] = $value;
            }

            $this->mStmt->execute();

            if ($this->mStmt->result_metadata() != false) {
                // The 'mysqlnd' (Native Driver) comes with the 'get_result()' that provides a real result object for stmt's
                if (method_exists($this->mStmt, "get_result")) {
                    $result = new Result( $this->mStmt->get_result() );

                } else if ($this->mStmt->store_result()) {
                    $result = new StmtResult($this->mStmt);

                } else {
                    return null;
                }

            } else {
                // Not a result producing query
                return null;
            }

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\Stmt")]
    public function execute(mixed &...$data): int {
        try {
            foreach ($data as $key => $value) {
                $this->mBoundValues[$key]["value"] = $value;
            }

            $this->mStmt->execute();

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->mStmt->affected_rows;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\Stmt")]
    public function close(): void {
        if ($this->mStmt != null) {
            try {
                $this->mStmt->close();

            } catch (mysqli_sql_exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);

            } finally {
                $this->mStmt = null;
                $this->mDatabase = null;
                $this->mBoundValues = [];
            }
        }
    }
}
