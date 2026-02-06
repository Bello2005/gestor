<?php

namespace App\Database;

use Illuminate\Database\PostgresConnection as BasePostgresConnection;
use PDO;

class PostgresConnection extends BasePostgresConnection
{
    /**
     * Run a select statement against the database and return a set of results.
     */
    public function select($query, $bindings = [], $useReadPdo = true)
    {
        return parent::select($query, $this->castBooleans($bindings), $useReadPdo);
    }

    /**
     * Run an insert statement against the database.
     */
    public function insert($query, $bindings = [])
    {
        return parent::insert($query, $this->castBooleans($bindings));
    }

    /**
     * Run an update statement against the database.
     */
    public function update($query, $bindings = [])
    {
        return parent::update($query, $this->castBooleans($bindings));
    }

    /**
     * Run a delete statement against the database.
     */
    public function delete($query, $bindings = [])
    {
        return parent::delete($query, $this->castBooleans($bindings));
    }

    /**
     * Execute an SQL statement and return the boolean result.
     */
    public function statement($query, $bindings = [])
    {
        return parent::statement($query, $this->castBooleans($bindings));
    }

    /**
     * Run an SQL statement and get the number of rows affected.
     */
    public function affectingStatement($query, $bindings = [])
    {
        return parent::affectingStatement($query, $this->castBooleans($bindings));
    }

    /**
     * Convert PHP booleans to PostgreSQL-compatible string literals.
     *
     * PDO's PostgreSQL driver converts PHP booleans to 0/1 (integers),
     * but PostgreSQL doesn't implicitly cast integers to booleans.
     * This converts booleans to 'true'/'false' strings which PostgreSQL accepts.
     */
    protected function castBooleans(array $bindings): array
    {
        foreach ($bindings as $key => $value) {
            if (is_bool($value)) {
                $bindings[$key] = $value ? 'true' : 'false';
            }
        }

        return $bindings;
    }
}
