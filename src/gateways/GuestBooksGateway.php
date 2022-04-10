<?php
class GuestBooksGateway {

    private $connection = null;

    public function __construct($db) {
        $this->connection = $db;
    }

    public function findAll() {
        $statement = "SELECT * FROM GuestBooks";

        try {
            $statement = $this->connection->query($statement);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getPage($page, $pageSize) {
        $statement = "SELECT * FROM GuestBooks LIMIT :limit OFFSET :offset;";
        $offset = ($page - 1) * $pageSize;

        try {
            $statement = $this->connection->prepare($statement);
            $statement->bindParam('limit', $pageSize, PDO::PARAM_INT);
            $statement->bindParam('offset', $offset, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id) {
        $statement = "SELECT * FROM GuestBooks WHERE Id = ?";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                return $result[0];
            }

            return null;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function add(Array $guestBook) {
        $statement = "INSERT INTO GuestBooks (Author, Title, Comment, Date) values (:author, :title, :comment, :date)";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array(
                'author' => $guestBook['author'],
                'title' => $guestBook['title'],
                'comment' => $guestBook['comment'],
                'date' => date('Y-m-d')
            ));

            return $statement->rowCount();
        } catch (PDOExcetpion $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id) {
        $statement = "DELETE FROM GuestBooks WHERE Id = ?";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array($id));

            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(Array $updatedBook) {
        $statement = "UPDATE GuestBooks SET
            Author = :author,
            Title = :title,
            Comment = :comment,
            Date = :date 
            WHERE Id = :id";

        try {
            $statement = $this->connection->prepare($statement);
            $statement->execute(array(
                'author' => $updatedBook['author'],
                'title' => $updatedBook['title'],
                'comment' => $updatedBook['comment'],
                'date' => date('Y-m-d'),
                'id' => $updatedBook['id']
            ));

            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
?>