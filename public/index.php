<?php

use DI\ContainerBuilder;
use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Slim\Psr7\UploadedFile;


require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

$container->set('upload_directory', __DIR__ . '/assets/img');

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents('home.php'));
    return $response;
});

$app->get('/chat', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents('chat.php'));
    return $response;
});

$app->get('/contacts', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents('contacts.php'));
    return $response;
});

$app->get('/api/contacts', function (Request $request, Response $response) {
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

$app->post('/api/contacts', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $name = $data["name"];
    $phone = $data["phone"];
    $address = $data["address"];
    $created_at = date('Y-m-d H:i:s');

    $directory = $this->get('upload_directory');
    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['image'];

    if ($uploadedFile == null || $uploadedFile == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Image is required'
        );
    } elseif ($name == null || $name == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Name is required'
        );
    } elseif ($phone == null || $phone == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Phone is required'
        );
    } elseif ($address == null || $address == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Address is required'
        );
    } else {
        try {

            $image = moveUploadedFile($directory, $uploadedFile);

            $sql = "INSERT INTO contacts (name, image, phone, address, created_at) VALUES (:name, :image, :phone, :address, :created_at)";

            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':created_at', $created_at);
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

$app->get('/api/contacts/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    if ($id == null || $id == "") {
        $data = array(
            'error' => 'true',
            'message' => 'Id is required'
        );
    } else {
        $sql = "SELECT * FROM contacts WHERE id=:id";

        try {
            $db = new Db();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
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
    '/api/contacts/{id}',
    function (Request $request, Response $response, array $args) {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $name = $data["name"];
        $phone = $data["phone"];
        $address = $data["address"];

        $directory = $this->get('upload_directory');
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['image'];

        if ($id == null || $id == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Id is required'
            );
        } elseif ($name == null || $name == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Name is required'
            );
        } elseif ($phone == null || $phone == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Phone is required'
            );
        } elseif ($address == null || $address == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Address is required'
            );
        } else {
            if ($uploadedFile->getSize() == 0) {
                $sql = "UPDATE contacts SET name = :name, address = :address, phone = :phone WHERE id = $id";
            } else {
                $sql = "UPDATE contacts SET name = :name, address = :address, phone = :phone, image = :image WHERE id = $id";
            }

            try {
                $db = new Db();
                $conn = $db->connect();

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':phone', $phone);

                if ($uploadedFile->getSize() > 0) {
                    $image = moveUploadedFile($directory, $uploadedFile);
                    $stmt->bindParam(':image', $image);
                }

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
    '/api/contacts/{id}',
    function (Request $request, Response $response) {
        $id = $request->getAttribute('id');

        if ($id == null || $id == "") {
            $data = array(
                'error' => 'true',
                'message' => 'Id is required'
            );
        } else {
            $sql = "DELETE FROM contacts WHERE id=:id";

            try {
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $id);
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

function moveUploadedFile($directory,  UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    // see http://php.net/manual/en/function.random-bytes.php
    $basename = bin2hex(random_bytes(8));
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

$app->run();
