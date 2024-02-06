<?php

namespace Framework;

use PDO;

class Database
{
    public $conn;

    /**
     * Database constructor for the database connection.
     * @param $config
     * @throws Exception
     */

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ];

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Could not connect to the database: {$e->getMessage()}");
        }
    }

    /**
     * Query the database
     * @param string $query
     * @param array $params
     * @return PDOStatement
     * @throws PDOException
     */
    public function query($query, $params = [])
    {
        try {
            $sth = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $sth->bindValue(":{$key}", $value);
            }
            $sth->execute();
            return $sth;
        } catch (PDOException $e) {
            throw new PDOException("Query failed to execute {$e->getMessage()}");
        }
    }
}
