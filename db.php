<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");

    class db{
        // Properties
        private $host = 'localhost';
        private $user = 'root';
        private $password = '';
        private $dbname = 'wt_project';

        // Connect
        public function connect(){
            $mysql_connect_str = "mysql:host=$this->host;dbname=$this->dbname;";
            $dbConnection = new PDO($mysql_connect_str, $this->user, $this->password);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
        }
    }
    class DbResponse {
        public $status;
        public $error;
        public $lastinsertid;
     }

    
   function getDatabase() {

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "wt_project"; 

    $db = new db();
    return $db;
 }
