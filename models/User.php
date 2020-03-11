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
        public function read($userID) {
            $query = "SELECT * FROM  users WHERE idusers=$userID";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
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
            //$this->user_id = htmlspecialchars(strip_tags($this->user_id));

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