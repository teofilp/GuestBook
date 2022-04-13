<?php

require (__DIR__.'/../gateways/GuestBooksGateway.php');
require (__DIR__.'/BaseController.php');

class GuestBooksController extends BaseController{

    private $connetion = null;
    private $requestMethod = null;
    private $mapper = null;

    private $gateway = null;

    public function __construct($connection, $requestMethod) {
        $this->connection = $connection;
        $this->requestMethod  = $requestMethod;
        $this->gateway = new GuestBooksGateway($connection);

        $this->mapper = array(
            'GET' => function() { return $this->getBooks(); },
            'POST' => function() { return $this->addBook(); },
            'PUT' => function() { return $this->updateBook(); },
            'DELETE' => function() { return $this->deleteBook(); }
        );
    }

    public function execute() {
        $response = $this->mapper[$this->requestMethod]();

        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function getBooks() {
        if (isset($_GET["id"])) {
            $result = $this->gateway->find($_GET["id"]);
            return $this->okResponse(json_encode($result));
        }

        $search = $_GET["search"];
        $page = $_GET["page"];
        $result = $this->gateway->getPage($page, $search);
        
        return $this->okResponse(json_encode($result));
    }

    public function addBook() {
        $body = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($body) || !$this->validateAddBook($body)) {
            return $this->badRequestResponse();
        }

        return $this->okResponse($this->gateway->add($body));
    }

    public function updateBook() {
        $body = json_decode(file_get_contents('php://input'), true);

        if (!isset($body) || !$this->validateUpdateBook($body)) {
            return $this->badRequestResponse();
        }

        return $this->okResponse($this->gateway->update($body));
    }

    public function deleteBook() {
        if (!isset($_GET["id"])) return $this->badRequestResponse();
        $id = htmlspecialchars($_GET["id"]);
        if (!isset($id)) {
            return $this->badRequestResponse();
        }

        return $this->okResponse($this->gateway->delete($id));
    }

    private function validateUpdateBook(Array $payload) {
        if (!isset($payload['author']) || 
        !isset($payload['title']) || 
        !isset($payload['comment']) ||
        !isset($payload["id"])) {
            return false;
        }

        return true;
    }

    private function validateAddBook(Array $payload) {
        if (!isset($payload['author']) || 
        !isset($payload['title']) || 
        !isset($payload['comment'])) {
            return false;
        }

        return true;
    }
}


?>