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
use im\database\BaseConnection;
use im\database\Result as BaseResult;
use im\database\Stmt as BaseStmt;
use mysqli;
use mysqli_sql_exception;

/**
 * Connection class for `MySQL` databases
 */
class Connection extends BaseConnection {

    /** @ignore */
    protected bool $mIsTransacting = false;

    /** @ignore */
    protected ?mysqli $mDatabase;

    /**
     * Create a new `MySQL` connection
     *
     * @note
     *      Use '127.0.0.1' instead of 'localhost' if you are on Windows >= 7.
     *
     * @param $host
     *      Host address for the database server
     *
     * @param $database
     *      Optional name of the database
     *
     * @param $user
     *      Optional user name
     *
     * @param $passwd
     *      Optional user password
     *
     * @param $port
     *      Optional server port, defaults to '3306'
     *
     * @throws im\exc\DBException
     *      Throws exception on error
     */
    public function __construct(string $host, string $database=null, string $user=null, string $passwd=null, int $port=3306) {
        // Let's enable proper exception reporting rather than normal error reporting
        mysqli_report(MYSQLI_REPORT_ALL);

        try {
            $this->mDatabase = new mysqli($host, $user, $passwd, null, $port);
            $this->mDatabase->set_charset("utf8");

            if ($database != null) {
                try {
                    $this->mDatabase->select_db($database);

                } catch (mysqli_sql_exception $e) {
                    $this->mDatabase->real_query("CREATE DATABASE IF NOT EXISTS " . $this->mDatabase->escape_string($database) . " DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
                    $this->mDatabase->select_db($database);
                }
            }

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Set the database to be used with this connection.
     *
     * @param $database
     *      Name of the database to use
     */
    public function setDatabase(string $database): void {
        try {
            $this->mDatabase->select_db($database);

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @internal
     */
    public function getMysqli(): mysqli {
        return $this->mDatabase;
    }

    /**
     * @inheritDoc
     */
    #[Override("im\database\BaseConnection")]
    public function beginTransaction(): bool {
        if (!$this->mIsTransacting) {
            try {
                $this->mIsTransacting = $this->mDatabase->autocommit(false);

            } catch (mysqli_sql_exception $e) {}
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
                $this->mIsTransacting = !$this->mDatabase->rollback();
                $this->mDatabase->autocommit(true);

            } catch (mysqli_sql_exception $e) {}
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
                $this->mIsTransacting = !$this->mDatabase->commit();
                $this->mDatabase->autocommit(true);

            } catch (mysqli_sql_exception $e) {}
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
                return "'".$this->mDatabase->real_escape_string($data)."'";

            } catch (mysqli_sql_exception $e) {
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
        return new Stmt($this, $sql);
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function enquire(string $sql, mixed ...$data): ?BaseResult {
        try {
            if (count($data) > 0) {
                $sql = $this->embedSQLValues($sql, ...$data);
            }

            $result = $this->mDatabase->query($sql, MYSQLI_STORE_RESULT);

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        return is_object($result) ? new Result($result) : null;
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function execute(string $sql, mixed ...$data): int {
        try {
            if (count($data) > 0) {
                $sql = $this->embedSQLValues($sql, ...$data);
            }

            $this->mDatabase->real_query($sql);
            $result = $this->mDatabase->affected_rows;

        } catch (mysqli_sql_exception $e) {
            throw new DBException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
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
        return "mysqli";
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function platform(): string {
        return "MySQL";
    }

    /**
     * @inheritdoc
     */
    #[Override("im\database\BaseConnection")]
    public function close(): void {
        if ($this->mDatabase != null) {
            try {
                $this->mDatabase->close();

            } catch (mysqli_sql_exception $e) {
                throw new DBException($e->getMessage(), $e->getCode(), $e);

            } finally {
                $this->mDatabase = null;
            }
        }
    }
}
