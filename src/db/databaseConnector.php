<?php 

class DatabaseConnector {
    private $connection = null;

    public function __construct() {
        $host = "localhost";
        $port = "8443";
        $dbName = "GuestBook";
        $user = "user1";
        $password = "test";

        try {
            $this->connection = new PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$dbName", $user, $password
            );
        } catch(PDOException $e) {
            echo $e->getMessage();
            exit($e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}

?>