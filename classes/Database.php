<?php
declare(strict_types=1);
ini_set('display_errors', 1);
error_reporting(E_ALL);


class Database
{
    protected PDO $connection;
    private const HOST = 'localhost';
    private const DB_NAME = 'secondhandstore';
    private const USERNAME = 'root';
    private const PASSWORD = '';




    public function __construct()
    {

        $this->connection = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME, self::USERNAME, self::PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
}
?>