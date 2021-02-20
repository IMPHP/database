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

use Stringable;
use im\Shift;
use im\util\Vector;

/**
 * Base Connection that drivers can extend from.
 *
 * @note
 *      The `escape()` method in this class uses a generic escape for `string` types.
 *      Depending on the database and/or it's character encoding, this may NOT
 *      provide a full-proff SQL safeguard. Any driver should replace this method and
 *      at the very least, deal with `string` types in a database/encoding specific way.
 */
abstract class BaseConnection implements Connection {

    /** @ignore */
    const CHR_ESC = ['\\', "\0", "\n", "\r", "'", '"', "\x1a"];

    /** @ignore */
    const CHR_REP = ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'];

    /**
     * @inheritDoc
     */
    #[Override("im\database\Connection")]
    public function escape(mixed $data): string {
        if (is_int($data) || is_float($data)) {
            return strval($data);

        } elseif (is_bool($data)) {
            return $data ? "1" : "0";

        } else if (is_string($data)) {
            return "'".str_replace(static::CHR_ESC, static::CHR_REP, $data)."'";

        } else if ($data instanceof Stringable) {
            $this->escape( strval($data) );

        } else {
            return "NULL";
        }
    }

    /**
     * Parse SQL String with placeholders
     *
     * This method is used to convert placeholders into database specific placeholders.
     * A driver class can extend this and add it's own specific scheme to the SQL
     * being parsed.
     *
     * You can optionally supply an array that will be populated with the original placeholder info.
     * This can be used internally by `enquire()` and `execute()` to ensure
     * that the correct data is added for each placeholder.
     *
     * __MySQL Example__
     * `QUERY WHERE column=%s` will be rewritten as `QUERY WHERE column=?`
     *
     * @note
     *      Do NOT parse SQL via this method BEFORE passing it to a method such as `execute()`
     *      or `enquire()`. This method is already used internally by these methods, which rely on
     *      library version of placeholders to do their work properly. 
     *
     * @param $sql
     *      The SQL string including formatting
     *
     * @param $params
     *      Optional array that will be populated with the original placeholder info
     *
     * @internal
     */
    public function parseSQLParam(string $sql, Vector &$params): string {
        return preg_replace_callback('/(?<=^|[^\\\])%[a-z]/', function($matches) use (&$params){
            $params->add($matches[0][-1]);

            return "?";

        }, $sql);
    }

    /**
     * Replace all placeholder in an SQL string.
     *
     * This method will replace all the placeholder with the values from
     * `$values`. While doing so, data will be converted into appropriate types and
     * `string` values will be excaped by the `escape()` method.
     *
     * __Placeholders__:
     *
     * | Chars | Type    |
     * | :---- | :------ |
     * | %s    | string  |
     * | %i    | integer |
     * | %d    | double  |
     *
     * @note
     *      This is automatically done in both `execute()` and `enquire()`,
     *      so there is no need to use this method, unless you want to use
     *      the output SQL for something else.
     *
     * @param $sql
     *      The SQL string including optional formatting
     *
     * @param $values
     *      Data for the $sql placeholders
     *
     * @return
     *      SQL string with replaced placeholders.
     */
    public function embedSQLValues(string $sql, mixed &...$values): string {
        $x = 0;
        $callback = function($matches) use (&$values, &$x){
            $type = $matches[0][-1];
            $value = $values[$x++];
            $value = match ($type) {
                's' => Shift::toString($value),
                'f' => Shift::toFloat($value),
                'i' => Shift::toInteger($value),
                default => null
            };

            return $this->escape($value);
        };

        $callback->bindTo($this);

        return preg_replace_callback('/(?<=^|[^\\\])%[a-z]/', $callback, $sql);
    }
}
