<?php
class Connect {
    
    private $host;
    private $user;
    private $pass;
    private $db; 
    
    public function dbConn() {
        $this->host = "localhost";
        $this->user = "root";
        $this->pass = "";
        $this->db = "assignme";

        $connection = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        } else {
            return $connection;
        }
    }
}
?>