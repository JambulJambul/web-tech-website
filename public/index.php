<?php

use DI\ContainerBuilder;
use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;


require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);

$app->get('/index.php', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents('homepage.php'));
    return $response;
});

$app->get('/api/showConts', function (Request $request, Response $response) {
    $sql = "SELECT * FROM contacts";

    try {
        $db = new Db();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $contacts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $result = array(
            'error' => 'false',
            'message' => 'Contacts fetched successfully',
            'data' => $contacts
        );

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $data = array(
            'error' => 'true',
            'message' => $e->getMessage()
        );

        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->post('/api/addConts', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $username = $data["username"];
    $phonenum = $data["phonenum"];

    if ($username == null || $username == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Name is required'
        );
    } elseif ($phonenum == null || $phonenum == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Phone is required'
        );
    } else {
        try {
            $sql = "INSERT INTO contacts (username, phonenum) VALUES (:username, :phonenum)";

            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':phonenum', $phonenum);
            $stmt->execute();

            $result = array(
                'error' => 'false',
                'message' => 'Contact added successfully',
            );

            $db = null;
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                'error' => 'true',
                'message' => $e->getMessage()
            );
        }
    }

    $response->getBody()->write(json_encode($data));
    return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
});

$app->get('/api/getCont/{cont_id}', function (Request $request, Response $response) {
    $cont_id = $request->getAttribute('cont_id');

    if ($cont_id == null || $cont_id == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Id is required'
        );
    } else {
        $sql = "SELECT * FROM contacts WHERE cont_id=:cont_id";

        try {
            $db = new Db();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cont_id', $cont_id);
            $stmt->execute();
            $contact = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;

            if ($contact == null || $contact == "") {
                $data = array(
                    'error' => 'true',
                    'message' => 'Contact not found'
                );
            } else {
                $result = array(
                    'error' => 'false',
                    'message' => 'Contact fetched successfully',
                    'data' => $contact
                );

                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            }
        } catch (PDOException $e) {
            $data = array(
                'error' => 'true',
                'message' => $e->getMessage()
            );
        }
    }

    $response->getBody()->write(json_encode($data));
    return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
});

$app->post(
    '/api/editConts/{cont_id}',
    function (Request $request, Response $response, array $args) {
        $cont_id = $request->getAttribute('cont_id');
        $data = $request->getParsedBody();
        $username = $data["username"];
        $phonenum = $data["phonenum"];
        if ($cont_id == null || $cont_id == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Id is required'
            );
        } elseif ($username == null || $username == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Name is required'
            );
        } elseif ($phonenum == null || $phonenum == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Phone is required'
            );
        } else {
            
                $sql = "UPDATE contacts SET username = :username, phonenum = :phonenum WHERE cont_id = $cont_id";
            

            try {
                $db = new Db();
                $conn = $db->connect();

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':phonenum', $phonenum);



                $stmt->execute();

                $data = array(
                    'error' => 'false',
                    'message' => 'Contact updated successfully'
                );

                $db = null;
                $response->getBody()->write(json_encode($data));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            } catch (PDOException $e) {
                $data = array(
                    'error' => 'true',
                    'message' => $e->getMessage()
                );
            }
        }

        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
);

$app->delete(
    '/api/delcontacts/{cont_id}',
    function (Request $request, Response $response) {
        $cont_id = $request->getAttribute('cont_id');

        if ($cont_id == null || $cont_id == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Id is required'
            );
        } else {
            $sql = "DELETE FROM contacts WHERE cont_id=:cont_id";

            try {
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':cont_id', $cont_id);
                $stmt->execute();
                $db = null;

                $data = array(
                    'error' => 'false',
                    'message' => 'Contact deleted successfully'
                );

                $db = null;
                $response->getBody()->write(json_encode($data));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
            } catch (PDOException $e) {
                $data = array(
                    'error' => 'true',
                    'message' => $e->getMessage()
                );
            }
        }

        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
);

$app->run();
