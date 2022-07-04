<?php

// declare(strict_types=1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
require_once 'vendor/autoload.php';
require 'chat_function.php';
return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('api/ping', function ($request, $response) {
        $output = ['msg' => 'RESTful API works, active and online!'];
        return $response->withJson($output, 200, JSON_PRETTY_PRINT);
    });

    $app->get('/api/chat/[{id}]', function ($request, $response, $args) {

        $service = new ChatService();
        $chatroom = $service->getChatMessages($args["id"]);
        $data = $chatroom->toJsonObject();
        $user_id = $request->getParam("user_id");

        if ($user_id == null) {
            return $response->withJson(["error" => "User ID mismatch/not found"], 400)
                ->withHeader("Content-type", "application/json");
        }

        $data["user_id"] = $user_id;
        return $response->withJson($data, 200)
            ->withHeader('Content-type', 'application/json');
    });

    $app->post("/api/chat", function ($request, $response) {
        $id1 = $request->getParam("id1");
        $id2 = $request->getParam("id2");

        $service = new ChatService();
        $result = $service->createChatRoom($id1, $id2);

        return $response->withJson(["result" => $result], 200)
            ->withHeader("Content-type", "application/json");
    });

    $app->put("/api/chat/[{id}]", function ($request, $response, $args) {
        $service = new ChatService();
        
        $result = $service->addMessage($args["id"], $request->getParam("sender_id"), $request->getParam("message"));

        return $response->withJson(["result" => $result], 200)
            ->withHeader("Content-type", "application/json");
    });

    $app->get("/api/chat/download/file", function ($request, $response, $args) {

        $file = $request->getParam("path");
        $resp = $response->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate')
            ->withHeader('Pragma', 'public')
            ->withHeader('Content-Length', filesize($file));

        readfile($file);
        return $resp;
    });

    $app->post("/api/chat/file/[{id}]", function ($request, $response, $args) {
        include "db_conn.php";

        $service = new ChatService();

        $data = json_encode(file_get_contents("php://input"), true);

        $img_name = $_FILES['my_image']['name'];
        $img_size = $_FILES['my_image']['size'];
        $tmp_name = $_FILES['my_image']['tmp_name'];
        $error = $_FILES['my_image']['error'];

        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid("File-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'upload/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);

            $sql = "INSERT INTO images(image_url) 
                            VALUES('$new_img_name')";
            mysqli_query($conn, $sql);

            $service->addMessage($args["id"], $request->getParam("sender_id"), $img_name, $img_upload_path, true);
        } else {
            $em = "You cant upload file of this type, try upload picture";
            header("Location: index.html?error=$em");
        }
    });

    $app->get("/api/contact/[{id}]", function ($request, $response, $args) {
        $service = new ChatService();

        $data = $service->getContact($args["id"]);

        return $response->withJson($data, 200)
            ->withHeader("Content-type", "application/json");
    });

    /*
    $app->post('/patients', function ($request, $response) {

        //form data
        $json = json_decode($request->getBody());
        $name = $json->name;
        $gender = $json->gender;
        $age = $json->age;
        $address = $json->address;
        $postcode = $json->postcode;
        $city = $json->city;
        $state = $json->state;
        $mobileno = $json->mobileno;
        $icuadmissiondate = '';
        $service = new PatientService();
        $dbs = $service->insertPatient($name, $gender, $age, $address, $postcode, $city, $state, $mobileno,$icuadmissiondate);

        $data = array(
            "insertStatus" => $dbs->status,
            "errorMessage" => $dbs->error
        );


        return $response->withJson($data, 200)
            ->withHeader('Content-type', 'application/json');
    });
    $app->get('/patients/[{id}]', function($request, $response, $args){
      
        $id = $args['id'];
  
        $service = new PatientService();
        $data = $service->getPatientViaId($id);
  
        return $response->withJson($data, 200)
                        ->withHeader('Content-type', 'application/json'); 
     }); 
  
     $app->put('/patients/[{id}]', function($request, $response, $args){
  
        $id = $args['id'];

        $json = json_decode($request->getBody());
        $name = $json->name;
        $gender = $json->gender;
        $age = $json->age;
        $address = $json->address;
        $postcode = $json->postcode;
        $city = $json->city;
        $state = $json->state;
        $mobileno = $json->mobileno;
  
        $service = new PatientService();
        $dbs = $service->updatePatientViaId($id, $name, $gender, $age, $address, $postcode, $city, $state, $mobileno);
  
        $data = Array(
           "updateStatus" => $dbs->status,
           "errorMessage" => $dbs->error
        );
  
        return $response->withJson($data, 200)
                        ->withHeader('Content-type', 'application/json');
     });  
  
     $app->put('/patients/status/[{id}]', function($request, $response, $args){
       
        //from url
        $id = $args['id'];
  
        //form data, from json data
        $json = json_decode($request->getBody());
        $status = $json->status;
  
        $service = new PatientService();
  
        $dbs = $service->updatePatientStatusViaId($id, $status);
  
        $data = Array(
           "updateStatus" => $dbs->status,
           "errorMessage" => $dbs->error,
           "status" => $status
        );
  
        return $response->withJson($data, 200)
                        ->withHeader('Content-type', 'application/json');
     }); 
     */
  
};
