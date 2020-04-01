<?php

    class User {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // User Properties
        public $user_id;
        public $username;
        public $password;
        public $email;
        public $firstname;
        public $lastname;


        public function __construct($db) {
            $this->conn = $db;
        }    
        
        //Get User
        public function read() {
            $query = "SELECT * FROM  users WHERE idusers=$this->user_id";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // User Login
        public function emailExists() {
            $query = "SELECT *
            FROM users
            WHERE email = ?
            LIMIT 0,1;";

            $stmt = $this->conn->prepare($query);

            $this->email = htmlspecialchars(strip_tags($this->email));

            $stmt->bindParam(1, $this->email);
 
            // execute the query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();

            // if email exists, assign values to object properties for easy access and use for php sessions
            if($num>0){
        
                // get record details / values
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                // assign values to object properties
                $this->user_id = $row['idusers'];
                $this->username = $row['username'];
                $this->password = $row['password'];
                $this->firstname = $row['firstname'];
                $this->lastname = $row['lastname'];

                
        
                // return true because email exists in the database
                return true;
            }
        
            // return false if email does not exist in the database
            return false;
        }

        // Register
        public function usernameExists() {
            $query = "SELECT *
            FROM users
            WHERE username = ?
            LIMIT 0,1;";

            $stmt = $this->conn->prepare($query);

            $this->email = htmlspecialchars(strip_tags($this->username));

            $stmt->bindParam(1, $this->username);
 
            // execute the query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();

            // if username exists, assign values to object properties for easy access and use for php sessions
            if($num>0){
        
                // get record details / values
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                // assign values to object properties
                $this->user_id = $row['idusers'];
                $this->email = $row['email'];
                $this->firstname = $row['firstname'];
                $this->lastname = $row['lastname'];

                
        
                // return true because username exists in the database
                return true;
            }
        
            // return false if username does not exist in the database
            return false;
        }

        //Create user
        public function create(){
            $query = "Call CreateUser(?, ?, ?, ?, ?);";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->firstname = htmlspecialchars(strip_tags($this->firstname));
            $this->lastname = htmlspecialchars(strip_tags($this->lastname));

            //Bind data
            $stmt->bindParam(1, $this->username, PDO::PARAM_STR, 50);
            $stmt->bindParam(2, $this->password, PDO::PARAM_STR, 200);
            $stmt->bindParam(3, $this->email, PDO::PARAM_STR, 200);
            $stmt->bindParam(4, $this->firstname, PDO::PARAM_STR, 200);
            $stmt->bindParam(5, $this->lastname, PDO::PARAM_STR, 200);

            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        //Update user
        public function update($userID){
            $query = "UPDATE users SET username = :username,
                                            password = :password,
                                            email = :email,
                                            firstname = :firstname,
                                            lastname = :lastname
                                    WHERE idusers= $userID";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->firstname = htmlspecialchars(strip_tags($this->firstname));
            $this->lastname = htmlspecialchars(strip_tags($this->lastname));

            //Bind data
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':lastname', $this->lastname);
            //$stmt->bindParam(':user_id', $this->user_id);

            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete user
        public function delete($userID){
            // CReate query
            $query = "DELETE FROM users WHERE idusers=$userID";

            $stmt = $this->conn->prepare($query);


            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
        
    }
?>