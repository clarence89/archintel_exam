<?php

namespace Services;

use Database\Database;

class ControllerService
{
    protected $query;
    protected $primary_key;
    protected $table;
    protected $where = false;
    protected $insert = false;
    protected $update = false;
    protected $delete = false;

    /**
     * Constructor
     *
     * @param string $primary_key The primary key of the table.
     * @param string $table The table to select from.
     * @param string|array $fields The fields to select. If an array is given, it will be imploded with a comma.
     *
     * @throws \Exception If the query is an insert or update query.
     */
    public function __construct($primary_key, $table, $fields = "*", $distinct = false)
    {
        $this->table = $table;
        $this->primary_key = $primary_key;
        if (is_array($fields)) {
            $fields = implode(",", $fields);
        }
        $this->query = "SELECT " . ($distinct ? "DISTINCT " : "") . "$fields FROM $this->table";
    }
    public function __destruct()
    {
        $this->query = null;
    }
    public function __invoke(): object
    {
        return $this->get();
    }


    /**
     * Validates that the query is a select query.
     *
     * This method checks if the query is intended for selecting data
     * and not for inserting or updating. Throws an exception if the
     * query is an insert or update type.
     *
     * @return bool True if the query is a select query.
     * @throws \Exception If the query is an insert or update query.
     */
    protected function validateSelect(): bool
    {
        if ($this->insert || $this->update || $this->delete) {
            throw new \Exception("Cannot use function on a insert, update, and delete query. Use save() instead.\n\n");
        }
        return true;
    }


    /**
     * Validates that the query is an insert or update query.
     *
     * Used internally to validate that the correct functions are being used.
     *
     * @return bool
     * @throws \Exception
     */
    protected function validateInsertUpdate(): bool
    {
        if (!($this->insert || $this->update || $this->delete)) {
            throw new \Exception("Cannot use function on a Select Statement. Use get(), first(), or last() instead.\n\n");
        }
        return true;
    }


    /**
     * Save the query to the database.
     *
     * @return object An object containing the results of the query.
     */

    public function save(): object
    {
        $this->validateInsertUpdate();
        $db = new Database();
        $result = $db->query($this->query);
        return $result;
    }


    /**
     * Retrieve all records of the query.
     *
     * @return object
     */
    public function get(): object
    {
        $this->validateSelect();
        $db = new Database();
        $result = $db->query($this->query);
        return $result;
    }


    /**
     * Retrieve the first record of the query.
     *
     * @return object An object of the first record of the query.
     */

    public function first(): object
    {
        $this->validateSelect();
        $this->query .= " LIMIT 1";
        $db = new Database();
        $result = $db->queryOne($this->query);
        return $result;
    }


    /**
     * Retrieve the last record of the query.
     *
     * @return object
     */


    public function last()
    {
        $this->validateSelect();
        $this->query .= " ORDER BY $this->primary_key DESC LIMIT 1";
        $db = new Database();
        $result = $db->queryOne($this->query);
        return $result;
    }


    /**
     * Adds an ORDER BY clause to the query.
     *
     * If $field_name is an array, multiple ORDER BY clauses will be added.
     * The $order parameter will be applied to all of them.
     *
     * @param array|string $field_name
     * @param string $order
     * @return ControllerService
     */
    public function orderBy($field_name, $order = "ASC"): ControllerService
    {
        $order = strtoupper($order) === "DESC" ? "DESC" : "ASC";

        if (is_array($field_name)) {
            $orderClauses = [];
            foreach ($field_name as $value) {
                $orderClauses[] = "$value $order";
            }
            $this->query .= " ORDER BY " . implode(', ', $orderClauses);
        } else {
            $this->query .= " ORDER BY $field_name $order";
        }

        return $this;
    }


    /**
     * Adds a GROUP BY clause to the query.
     *
     * @param string|array $field_name The field name(s) to group by.
     * @return $this
     */

    public function groupBy($field_name): ControllerService
    {
        if (is_array($field_name)) {
            $groupClauses = [];
            foreach ($field_name as $value) {
                $groupClauses[] = "$value";
            }
            $this->query .= " GROUP BY " . implode(', ', $groupClauses);
        } else {
            $this->query .= " GROUP BY $field_name";
        }

        return $this;
    }


    /**
     * Perform an INNER JOIN on the query
     *
     * @param string $table The table to join
     * @param string $parent_key The key in the parent table
     * @param string $child_key The key in the child table
     *
     * @return ControllerService
     */
    public function innerJoin($table, $parent_key, $child_key): ControllerService
    {
        $this->query .= " INNER JOIN $table ON $parent_key = $child_key ";
        return $this;
    }

    /**
     * Add a LEFT JOIN clause to the query.
     *
     * @param string $table The table to join.
     * @param string $parent_key The key of the parent table.
     * @param string $child_key The key of the child table.
     *
     * @return static
     */
    public function leftJoin($table, $parent_key, $child_key): ControllerService
    {
        $this->query .= " LEFT JOIN $table ON $parent_key = $child_key ";
        return $this;
    }


    /*************  ✨ Codeium Command ⭐  *************/
    /**
     * Add a RIGHT JOIN clause to the query.
     *
     * @param string $table The table to join.
     * @param string $parent_key The key of the parent table.
     * @param string $child_key The key of the child table.
     *
     * @return static
     */
    /******  b2397470-ed27-4ae7-bfad-d36f8b37dab7  *******/
    public function rightJoin($table, $parent_key, $child_key): ControllerService
    {
        $this->query .= " RIGHT JOIN $table ON $parent_key = $child_key ";
        return $this;
    }


    /**
     * Add a WHERE clause to the query.
     *
     * @param string $field_name The field name to query.
     * @param mixed $value The value to query for.
     * @param string $operator The operator to use. IN, =, !=, <, >, <=, >=.
     *
     * @return ControllerService
     *
     * @throws \InvalidArgumentException If the value is not an array when using the IN operator.
     */
    public function where($field_name, $value, $operator = "="): ControllerService
    {
        if ($operator === "IN") {
            if (!is_array($value)) {
                throw new \InvalidArgumentException("Value must be an array when using 'IN' operator.\n\n");
            }
            $valueList = implode(',', array_map(function ($item) {
                return "'" . addslashes($item) . "'";
            }, $value));

            if ($this->where == false) {
                $this->query .= " WHERE $field_name IN ($valueList) ";
                $this->where = true;
            } else {
                $this->query .= " AND $field_name IN ($valueList) ";
            }
        } else {
            if ($this->where == false) {
                $this->query .= " WHERE $field_name $operator '" . addslashes($value) . "' ";
                $this->where = true;
            } else {
                $this->query .= " AND $field_name $operator '" . addslashes($value) . "' ";
            }
        }
        return $this;
    }


    /**
     * Add an OR WHERE clause to the query.
     *
     * This method allows chaining of additional conditions to an existing WHERE clause using OR logic.
     * The method must be preceded by a call to where() to initialize the WHERE clause.
     *
     * @param string $field_name The field name to query.
     * @param mixed $value The value to query for.
     * @param string $operator The operator to use. IN, =, !=, <, >, <=, >=.
     *
     * @return ControllerService
     *
     * @throws \InvalidArgumentException If the value is not an array when using the IN operator or if used before where().
     */


    public function orWhere($field_name, $value, $operator = "="): ControllerService
    {
        if ($this->where == true) {
            if ($operator === "IN") {
                if (!is_array($value)) {
                    throw new \InvalidArgumentException("Value must be an array when using 'IN' operator.\n\n");
                }
                $valueList = implode(',', array_map(function ($item) {
                    return "'" . addslashes($item) . "'";
                }, $value));

                $this->query .= " OR $field_name IN ($valueList) ";
            } else {
                $this->query .= " OR $field_name $operator '" . addslashes($value) . "' ";
            }
        } else {
            throw new \InvalidArgumentException("You must use where() before using orWhere()\n\n");
        }
        return $this;
    }


    /**
     * Update the database table with the given data.
     *
     * @param array $data An associative array of field names and values to update.
     *
     * @return ControllerService
     *
     * @throws \InvalidArgumentException If the data is not an associative array.
     */


    public function update(array $data): ControllerService
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("Data must be an associative array in insert() method\n\n");
        }
        $fields = [];
        foreach ($data as $field => $value) {
            $fields[] = "$field = '$value'";
        }
        $this->query = "UPDATE $this->table SET " . implode(', ', $fields);
        $this->where = false;
        $this->insert = false;
        $this->update = true;
        $this->delete = false;
        return $this;
    }


    /**
     * Inserts a new record into the table with the specified data.
     *
     * @param array $data An associative array where keys are column names and values are the values to be inserted.
     * @return ControllerService Returns the current instance of the ControllerService for method chaining.
     */
    public function insert(array $data): ControllerService
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("Data must be an associative array in insert() method\n\n");
        }
        $fields = [];
        $values = [];

        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = "'$value'";
        }
        $this->query = "INSERT INTO $this->table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
        $this->where = false;
        $this->insert = true;
        $this->update = false;
        $this->delete = false;
        return $this;
    }

    public function delete(): ControllerService
    {

        $this->query = "DELETE FROM $this->table";
        $this->where = false;
        $this->insert = false;
        $this->update = false;
        $this->delete = true;
        return $this;
    }
}
