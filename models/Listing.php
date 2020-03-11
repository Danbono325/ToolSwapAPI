<?php

    class Listing {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // User Properties
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
        public function readListing($listingID) {
            $query = "SELECT * FROM listings WHERE idlistings = $listingID;";
        
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
        public function create(){
            $query = "CALL CreateListing(?, ?, ?, ?, ?, ?, ?);";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->description = htmlspecialchars(strip_tags($this->description));
            // $this->email = htmlspecialchars(strip_tags($this->email));
            // $this->firstname = htmlspecialchars(strip_tags($this->firstname));
            // $this->lastname = htmlspecialchars(strip_tags($this->lastname));

            //Bind data
            $stmt->bindParam(1, $this->userID, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->title, PDO::PARAM_STR, 45);
            $stmt->bindParam(3, $this->description, PDO::PARAM_STR, 6,000);
            $stmt->bindParam(4, $this->expectedDays, PDO::PARAM_INT);
            $stmt->bindParam(5, $this->expectedWeeks, PDO::PARAM_INT);
            $stmt->bindParam(6, $this->expectedMonths, PDO::PARAM_INT);
            $stmt->bindParam(7, $this->expectedYears, PDO::PARAM_INT);


            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        

        //Update listing
        public function update($listingID){
            $query = "UPDATE listings SET title = :title,
                                            description = :description,
                                            expectedDays = :expectedDays,
                                            expectedWeeks = :expectedWeeks,
                                            expectedMonths = :expectedMonths,
                                            expectedYears = :expectedYears
                                    WHERE idlistings= $listingID";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->description = htmlspecialchars(strip_tags($this->description));

            //Bind data
            $stmt->bindParam(1, $this->userID, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->title, PDO::PARAM_STR, 45);
            $stmt->bindParam(3, $this->description, PDO::PARAM_STR, 6,000);
            $stmt->bindParam(4, $this->expectedDays, PDO::PARAM_INT);
            $stmt->bindParam(5, $this->expectedWeeks, PDO::PARAM_INT);
            $stmt->bindParam(6, $this->expectedMonths, PDO::PARAM_INT);
            $stmt->bindParam(7, $this->expectedYears, PDO::PARAM_INT);

            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update listing as completed

        // Delete Listing
        public function delete($listingID){
            //Delete Query
            $query = "CALL DeleteListing($listingID);";

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