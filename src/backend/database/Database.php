<?php
namespace Database;
class Database
{
    private $host;
    private $user;
    private $pass;
    private $db;
    private $conn;

    public function __construct()
    {
        $this->host = getenv('MYSQL_HOST') ?: 'localhost';
        $this->user = getenv('MYSQL_USER') ?: 'root';
        $this->pass = getenv('MYSQL_PASSWORD') ?: '';
        $this->db = getenv('MYSQL_DATABASE') ?: 'access_logs';
        $this->connect();
    }
    public function escape($string)
    {
        return $this->conn->real_escape_string($string);
    }
    private function connect()
    {
        $this->conn = new \mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($this->conn->connect_errno > 0) {
            throw new \Exception('Connection Error: ' . $this->conn->connect_error);
        }
    }
    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
    public function query($query)
    {
        try {
            $result = $this->conn->query($query);
            if (!$result) {
                $error = $this->conn->error;
                $error .= "<br>Query: " . $query;
                if (strpos($error, 'Duplicate entry') !== false) {
                    $error = str_replace('Duplicate entry', 'Duplicate record', $error);
                }
                throw new \Exception($error);
            }

            $result_data = [];
            $is_update_query = stripos($query, 'UPDATE') === 0; // Check if the query is an UPDATE
            $is_insert_query = stripos($query, 'INSERT') === 0; // Check if the query is an UPDATE
            $is_delete_query = stripos($query, 'DELETE') === 0; // Check if the query is an UPDATE

            if ($is_delete_query) {
                // For DELETE queries, return the count of affected rows
                $affected_rows = $this->conn->affected_rows;
                return (object)[
                    "count" => $affected_rows,
                    "result" => "Deleted row(s)" . $affected_rows, // No result data for DELETE
                    "query" => $query
                ];
            }

            else if ($is_insert_query) {
                // For INSERT queries, return the ID of the inserted record
                $insert_id = $this->conn->insert_id;
                return (object)[
                    "count" => 1,
                    "result" => $insert_id, // No result data for INSERT
                    "query" => $query
                ];
            }
            else if ($is_update_query) {
                // For UPDATE queries, return the count of affected rows
                $affected_rows = $this->conn->affected_rows;

                return (object)[
                    "count" => $affected_rows,
                    "result" => "Updated row(s)" . $affected_rows . "", // No result data for UPDATE
                    "query" => $query
                ];
            } else {
                // For SELECT queries, fetch the results as before
                    while ($row = $result->fetch_assoc()) {
                        $result_data[] = $row;
                    }
                return (object)[
                    "count" => $result->num_rows,
                    "result" => $result_data,
                    "query" => $query
                ];
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br><br> Query: " . $query . ",<br><br> " . $e->getCode();
        }
    }
    public function queryOne($query)
    {
        try {
            $result = $this->conn->query($query);
            if (!$result) {
                $error = $this->conn->error;
                $error .= "<br>Query: " . $query;
                if (strpos($error, 'Duplicate entry') !== false) {
                    $error = str_replace('Duplicate entry', 'Duplicate record', $error);
                }
                throw new \Exception($error);
            }

            $result_data = [];
            $is_update_query = stripos($query, 'UPDATE') === 0; // Check if the query is an UPDATE
            $is_insert_query = stripos($query, 'INSERT') === 0; // Check if the query is an UPDATE
            if ($is_insert_query) {
                // For INSERT queries, return the ID of the inserted record
                $insert_id = $this->conn->insert_id;
                return (object)[
                    "count" => 1,
                    "result" => null, // No result data for INSERT
                    "query" => $query
                ];
            } else if ($is_update_query) {
                // For UPDATE queries, return the count of affected rows
                $affected_rows = $this->conn->affected_rows;

                return (object)[
                    "count" => $affected_rows,
                    "result" => null, // No result data for UPDATE
                    "query" => $query
                ];
            } else {
                // For SELECT queries, fetch the results as before
                if ($result->num_rows == 1) {
                    $result_data = $result->fetch_assoc();
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $result_data[] = $row;
                    }
                }
                return (object)[
                    "count" => $result->num_rows,
                    "result" => $result_data,
                    "query" => $query
                ];
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "<br><br> Query: " . $query . ",<br><br> " . $e->getCode();
        }
    }
}
