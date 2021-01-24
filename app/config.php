<?php
class Database {

    public function getConnection($db_host, $db_name, $db_user, $db_pass){
         $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_pass);      
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $this->conn;
    }
 }

?>
