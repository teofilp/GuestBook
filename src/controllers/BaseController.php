<?php

class BaseController {

    public function __construct() {}
    
    protected function badRequestResponse() {
        return [
            'status_code_header' => 'HTTP/1.1 400 Bad Request',
            'body' => json_encode([
                'error' => 'invalid request'
            ])
        ];
    }

    protected function notFoundResponse() {
        return [
            'status_code_header' => 'HTTP/1.1 404 Not Found',
            'body' => null
        ];
    }

    protected function okResponse($payload) {
        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $payload
        ];
    }
}


?>