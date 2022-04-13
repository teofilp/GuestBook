<?php

require (__DIR__.'/../gateways/UsersGateway.php');
require (__DIR__.'/BaseController.php');

class UsersController extends BaseController{

    private $connetion = null;
    private $requestMethod = null;
    private $mapper = null;

    private $gateway = null;

    public function __construct($connection, $requestMethod) {
        $this->connection = $connection;
        $this->requestMethod  = $requestMethod;
        $this->gateway = new UsersGateway($connection);

        $this->mapper = array(
            'POST' => function() { return $this->login(); },
        );
    }

    public function execute() {
        $response = $this->mapper[$this->requestMethod]();

        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);

        $user = $this->gateway->findByUsername($data["username"]);

        if (!isset($user)) {
            return $this->notFoundResponse();
        }

        if (!strcmp($user["Password"], $data["password"])) {
            setcookie("userId", $user["Id"]);

            return $this->okResponse(true);
        }
    }
}


?>