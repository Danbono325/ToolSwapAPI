<?php

    class Listing {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // Listing Properties
        public $userID;
        public $idlistings;
        public $title;
        public $description;
        public $expectedDays;
        public $expectedWeeks;
        public $expectedMonths;
        public $expectedYears;
        public $completed;


        public function __construct($db) {
            $this->conn = $db;
        }    
        
        //Get Listings for home screen
        public function readAllUncompleted() {
            $query = "SELECT * FROM listings where completed = 0;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Get Listing Details
        public function readListing() {
            $query = "SELECT * FROM listings WHERE idlistings = $this->idlistings;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Confirm user listing
        public function userListingConfirm($userID) {
            $query = "SELECT * FROM listings l JOIN users_listings ul ON ul.listings_idlistings = $this->idlistings LIMIT 0,1; WHERE ul.users_idusers=$userID";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Get a users listing
        public function readUsersListings($userID) {
            $query = "SELECT * FROM listings l JOIN users_listings ul ON ul.listings_idlistings = l.idlistings WHERE ul.users_idusers=$userID;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Create Listing
        public function create($userID){
            $query = "CALL CreateListing(:curUser, :title, :description, :days, :weeks, :months, :years);";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->description = htmlspecialchars(strip_tags($this->description));
            

            //Bind data
            $stmt->bindParam(':curUser', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':title', $this->title, PDO::PARAM_STR, 45);
            $stmt->bindParam(':description', $this->description, PDO::PARAM_STR, 6,000);
            $stmt->bindParam(':days', $this->expectedDays, PDO::PARAM_INT);
            $stmt->bindParam(':weeks', $this->expectedWeeks, PDO::PARAM_INT);
            $stmt->bindParam(':months', $this->expectedMonths, PDO::PARAM_INT);
            $stmt->bindParam(':years', $this->expectedYears, PDO::PARAM_INT);


            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        

        //Update listing
        public function update(){
            $query = "UPDATE listings SET title = :title,
                                            description = :description,
                                            expectedDays = :expectedDays,
                                            expectedWeeks = :expectedWeeks,
                                            expectedMonths = :expectedMonths,
                                            expectedYears = :expectedYears
                                    WHERE idlistings= $this->idlistings";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->description = htmlspecialchars(strip_tags($this->description));

            //Bind data
            //$stmt->bindParam(1, $this->userID, PDO::PARAM_INT);
            $stmt->bindParam(":title", $this->title, PDO::PARAM_STR, 45);
            $stmt->bindParam(":description", $this->description, PDO::PARAM_STR, 6,000);
            $stmt->bindParam(":expectedDays", $this->expectedDays, PDO::PARAM_INT);
            $stmt->bindParam(":expectedWeeks", $this->expectedWeeks, PDO::PARAM_INT);
            $stmt->bindParam(":expectedMonths", $this->expectedMonths, PDO::PARAM_INT);
            $stmt->bindParam(":expectedYears", $this->expectedYears, PDO::PARAM_INT);

            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update listing as completed
        public function updateAsComplete(){
            $query = "UPDATE listings SET completed = 1 WHERE idlistings= $this->idlistings";

            $stmt = $this->conn->prepare($query);

            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Delete Listing
        public function delete(){
            //Delete Query
            $query = "CALL DeleteListing($this->idlistings);";

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