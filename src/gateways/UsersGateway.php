<?php 

class UsersGateway {

    private $connection = null;

    public function __construct($db) {
        $this->connection = $db;
    }

    public function findAll() {
        $statement = "SELECT * FROM Users";

        try {
            $statement = $this->connection->query($statement);
            return $statement->fetchAll(PDO::FETCH_ASSOC); 
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id) {
        $statement = "SELECT * FROM Users WHERE Id = ?";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                return $result[0];
            } 

            return null;
        } catch (PDOExcetpion $e) {
            exit($e->getMessage());
        }
    }

    public function findByUsername($username) {
        $statement = "SELECT * FROM Users WHERE Username = ?";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array($username));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                return $result[0];
            }

            return null;
        } catch(PDOExcetion $e) {
            exit($e->getMessage());
        }
    }
}

?>