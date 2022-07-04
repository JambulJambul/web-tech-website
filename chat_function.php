<?php

require 'db.php';
require 'chat_model.php';

class ChatService
{
    private $db;

    public function __construct()
    {
        $this->db = getDatabase()->connect();

    }

    public function getChatMessages($roomID) {
        try {
            $sql = "SELECT * FROM chatrooms WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $roomID);
            $stmt->execute();
            $row_count = $stmt->rowCount();
    
            $jsonData = null;

            $chatroom = null;
            if ($row_count) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
                    $path = $row["chat_file"];
                    $raw = file_get_contents(__DIR__ . "/../" . $path);
                    $jsonData = json_decode($raw, true);
                    

                    $chatroom = new Chatroom($jsonData, $row["user1_id"], $row["user2_id"], $row["last_modified"]);
                }

                return $chatroom;
            }
    
        } catch (PDOException $e) {
            $error = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $error;

            return $dbs;
        }
    }

    public function createChatRoom($id1, $id2) {
        try {
            $sql = "INSERT INTO chatrooms (`user1_id`, `user2_id`, `chat_file`) VALUES (:id1, :id2, :file_path)";

            if ($id1 > $id2) {
                $temp = $id2;
                $id2 = $id1;
                $id1 = $temp;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id1", $id1);
            $stmt->bindParam("id2", $id2);

            $path = "storage/chat_" . $id1 . "_" . $id2 . ".json";
            $stmt->bindParam("file_path", $path);
            $stmt->execute();

            return ["status" => 1];

        } catch (PDOException $e) {
            $error = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $error;

            return $dbs;
        }
    }

    public function addMessage($roomID, $sender, $message, $payload = null, $isFile = false) {
        $chatroom = null;
        $path = null;
        try {
            $sql = "SELECT * FROM chatrooms WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $roomID);
            $stmt->execute();
            $row_count = $stmt->rowCount();
    
            $jsonData = null;

            if ($row_count) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
                    $path = $row["chat_file"];
                    $raw = file_get_contents(__DIR__ . "/../" . $path);
                    $jsonData = json_decode($raw, true);
                    

                    $chatroom = new Chatroom($jsonData, $row["user1_id"], $row["user2_id"], $row["last_modified"]);
                }

            }
    
        } catch (PDOException $e) {
            $error = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $error;

            return $dbs;
        }

        $current = new DateTime();
        $time = $current->format(DateTimeInterface::ISO8601);
        $chatroom->lastModified = $time;

        $chat = new Chat($sender, $message, $time, $isFile, $payload);

        array_push($chatroom->chatList, $chat);

        try {
            $sql = "UPDATE chatrooms SET last_modified = :tm";

            $tmp = $current->format("Y-m-d H:i:s");
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("tm", $tmp);
            $stmt->execute();
    
        } catch (PDOException $e) {
            $error = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $error;

            return $dbs;
        }

        $newJson = $chatroom->toJsonObject();
        file_put_contents(__DIR__ . "/../" . $path, json_encode($newJson));

        return ["status" => 1];
    }

    public function getContact($userId) {
        try {
            $sql = "SELECT * FROM contacts WHERE owner = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $userId);
            $stmt->execute();
            $row_count = $stmt->rowCount();
    
            $jsonData = null;
            $jsonData["user_id"] = $userId;
            $jsonData["contacts"] = array();

            if ($row_count) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($jsonData["contacts"], ["id" => $row["user_id"], "name" => $row["name"]]);
                }

            }
    
        } catch (PDOException $e) {
            $error = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $error;

            return $dbs;
        }

        return $jsonData;
    }

    /*
    function insertPatient($name, $gender, $age, $address, $postcode, $city, $state, $mobileno,$icuadmissiondate)
    {

        try {

            $sql = "INSERT INTO patients(name, gender, age, address, postcode, city, state, mobileno,icuadmissiondate) 
                   VALUES (:name, :gender, :age, :address, :postcode, :city, :state, :mobileno, NOW())";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $name);
            $stmt->bindParam("gender", $gender);
            $stmt->bindParam("age", $age);
            $stmt->bindParam("address", $address);
            $stmt->bindParam("postcode", $postcode);
            $stmt->bindParam("city", $city);
            $stmt->bindParam("state", $state);
            $stmt->bindParam("mobileno", $mobileno);
            $stmt->execute();

            $dbs = new DbResponse();
            $dbs->status = true;
            $dbs->error = "none";
            $dbs->lastinsertid = $this->db->lastInsertId();

            return $dbs;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $errorMessage;

            return $dbs;
        }
    }

    function getAllPatients()
    {
        $sql = "SELECT *
                FROM patients";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $row_count = $stmt->rowCount();

        $data = array();

        if ($row_count) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $patient = new Patient();
                $patient->id = $row['id'];
                $patient->name = $row['name'];
                $patient->gender = $row['gender'];
                $patient->age = $row['age'];
                $patient->address = $row['address'];
                $patient->postcode = $row['postcode'];
                $patient->city = $row['city'];
                $patient->state = $row['state'];
                $patient->mobileno = $row['mobileno'];
                $patient->status = $row['status'];


                $admissiondate = $row['admissiondate'];
                $patient->admissiondate = get_response_time($admissiondate);

                $icuadmissiondate = $row['icuadmissiondate'];
                $patient->icuadmissiondate = get_response_time($icuadmissiondate);

                $clinicaldeathdate = $row['clinicaldeathdate'];
                $patient->clinicaldeathdate = get_response_time($clinicaldeathdate);

                $dischargedate = $row['dischargedate'];
                // $patient->dischargedate = get_response_time($dischargedate);

                array_push($data, $patient);
            }
        }

        return $data;
    }

    function getPatientViaId($id)
    {

        $sql = "SELECT *
                FROM patients
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $row_count = $stmt->rowCount();

        $patient = new Patient();

        if ($row_count) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $patient->id = $row['id'];
                $patient->name = $row['name'];
                $patient->gender = $row['gender'];
                $patient->age = $row['age'];
                $patient->address = $row['address'];
                $patient->postcode = $row['postcode'];
                $patient->city = $row['city'];
                $patient->state = $row['state'];
                $patient->mobileno = $row['mobileno'];
                $patient->status = $row['status'];

                //$admissiondate = $row['admissiondate'];
                //$frontendadmissiondate = date("d-m-Y",strtotime($admissiondate));
                //$patient->admissiondate = $frontendadmissiondate;

                $admissiondate = $row['admissiondate'];
                $patient->admissiondate = get_response_time($admissiondate);

                $icuadmissiondate = $row['icuadmissiondate'];
                $patient->icuadmissiondate = get_response_time($icuadmissiondate);

                $clinicaldeathdate = $row['clinicaldeathdate'];
                $patient->clinicaldeathdate = $clinicaldeathdate;

                $dischargedate = $row['dischargedate'];
                $patient->dischargedate = get_response_time($dischargedate);
            }
        }

        return $patient;
    }

    function updatePatientViaId($id, $name, $gender, $age, $address, $postcode, $city, $state, $mobileno)
    {

        $sql = "UPDATE patients
                SET name = :name,
                    gender = :gender,
                    age = :age,
                    address = :address,
                    postcode = :postcode,
                    city = :city,
                    state = :state,
                    mobileno = :mobileno
                WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->bindParam("name", $name);
            $stmt->bindParam("gender", $gender);
            $stmt->bindParam("age", $age);
            $stmt->bindParam("address", $address);
            $stmt->bindParam("postcode", $postcode);
            $stmt->bindParam("city", $city);
            $stmt->bindParam("state", $state);
            $stmt->bindParam("mobileno", $mobileno);
            $stmt->execute();

            $dbs = new DbResponse();
            $dbs->status = true;
            $dbs->error = "none";

            return $dbs;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $errorMessage;

            return $dbs;
        }
    }

    function updatePatientStatusViaId($id, $status)
    {

        $sql = "";

        if (strcmp($status, "2") == 0) {
            $sql = "UPDATE patients
                   SET status = :status,
                       icuadmissiondate = NOW()
                   WHERE id = :id";
        }

        if (strcmp($status, "3") == 0) {
            $sql = "UPDATE patients
                   SET status = :status,
                       clinicaldeathdate = NOW()
                   WHERE id = :id";
        }

        if (strcmp($status, "4") == 0) {
            $sql = "UPDATE patients
                   SET status = :status,
                       dischargedate = NOW()
                   WHERE id = :id";
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->bindParam("status", $status);
            $stmt->execute();

            $dbs = new DbResponse();
            $dbs->status = true;
            $dbs->error = "none";

            return $dbs;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();

            $dbs = new DbResponse();
            $dbs->status = false;
            $dbs->error = $errorMessage;

            return $dbs;
        }
    }
    function close() {
        try {
           $this->db = null;   
        }
        catch(PDOException $e) {
           $errorMessage = $e->getMessage();
           return 0;
        } 
    }
    */
}
function get_response_time($datetime, $full = false) {

    if ($datetime == '0000-00-00 00:00:00')
       return "none";

    if ($datetime == '0000-00-00')
       return "none";

    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
       'y' => 'year',
       'm' => 'month',
       'w' => 'week',
       'd' => 'day',
       'h' => 'hour',
       'i' => 'minute',
       's' => 'second',
    );
    
    foreach ($string as $k => &$v) {
       if ($diff->$k) {
          $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
       } else {
          unset($string[$k]);
       }
    }

    if (!$full) $string = array_slice($string, 0, 1);
       return $string ? implode(', ', $string) . ' way' : 'at the moment';
 }
