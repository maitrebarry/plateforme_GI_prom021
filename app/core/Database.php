<?php

class Database
{
    private ?PDO $database = null;

    public function connect(): PDO
    {
        $dsn = 'mysql:host=' . DBHOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        return new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ]);
    }

    public function bdd(): PDO
    {
        if ($this->database === null) {
            $this->database = $this->connect();
        }

        return $this->database;
    }
}
