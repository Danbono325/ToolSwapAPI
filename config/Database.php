<?php 
class Database {

    private $host = 'localhost';
    private $user = 'root';
    private $password = 'baseball325';
    private $dbname = 'toolswap2';
    private $conn;

    // Set DSN - DATA SOURCE NAME
    // private $dsn = 'mysql:host='.$host.';dbname='.$dbname;

    // Create a PDO instance (connection)
    // $pdo = new PDO($dsn, $user, $password);
    // $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    public function dbConnection(){
        $this->conn = null;

        try{
            $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
        
        return $this->conn;
    }
}
?>