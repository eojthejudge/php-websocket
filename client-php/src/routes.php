<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

return function (App $app) {
    $app->post('/client-php/', function (Request $request, Response $response, array $args) {
        try {
            // decode data from the caller
            $GLOBALS['body'] = json_decode($request->getBody());
            /**
             * Send to Socket
             */
            \Ratchet\Client\connect('ws://127.0.0.1:8080')->then(function($conn) {
                $conn->send(
                    json_encode(array(
                        "user" => $GLOBALS['body']->user,
                        "group" => $GLOBALS['body']->group,
                    ))
                );
                $conn->close();
                $this->get('logger')->debug("Sent messages successfully: user => " . $GLOBALS['body']->user);
            }, function ($e) {
                $this->get('logger')->info("Can not connect: {$e->getMessage()}");
            });
            echo json_encode(array("status" => true));
        } catch (Exception $e) {
            $this->get('logger')->info("Exception: ", $e->getMessage());
            echo json_encode(array("status" => false)); 
        }
        return $response;
    });
};

    