<?php

    class Skill {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // Skill Properties
        public $description;

        public function __construct($db) {
            $this->conn = $db;
        }

        //GET ALL OF A USERS SKILLS
        public function readUserSkills($userID) {
            // SELECT query
            $query = "SELECT * FROM skills s 
                        JOIN skills_user su ON su.skills_idskills = s.idskills 
                        WHERE su.users_idusers = $userID;";

            $stmt = $this->conn->prepare($query);

            // Execute Query
            $stmt->execute();

            return $stmt;
        }

        // ADD A SKILL TO USER
        public function addSkill($userID) {
            // Add query
            $query = "CALL CreateUserSkill(:curUser, :description);";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':curUser', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':description', $this->description, PDO::PARAM_STR, 45);

            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // REMOVE A SKILL FROM USER
        public function removeSkill($userID) {
            // Add query
            $query = "CALL DeleteUserSkill(:curUser, :description);";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':curUser', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':description', $this->description, PDO::PARAM_STR, 45);

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