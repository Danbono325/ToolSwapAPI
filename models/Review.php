<?php
    class Review {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // Review Properties
        public $expectationScore;
        public $timeframeScore;
        public $budgetScore;
        public $description;


        public function __construct($db) {
            $this->conn = $db;
        }    
        
        // Get all reviews on a user
        // Create Listing
        public function create($userID, $listingID){
            $query = "CALL CreateReview(?, ?, ?, ?, ?, ?);";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->expectationScore = htmlspecialchars(strip_tags($this->expectationScore));
            $this->timeframeScore = htmlspecialchars(strip_tags($this->timeframeScore));
            $this->budgetScore = htmlspecialchars(strip_tags($this->budgetScore));
            $this->description = htmlspecialchars(strip_tags($this->description));

            //Bind data
            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->bindParam(2, $listingID, PDO::PARAM_INT);
            $stmt->bindParam(3, $this->expectationScore, PDO::PARAM_STR, 3);
            $stmt->bindParam(4, $this->timeframeScore, PDO::PARAM_STR, 3);
            $stmt->bindParam(5, $this->budgetScore, PDO::PARAM_STR, 3);
            $stmt->bindParam(6, $this->description, PDO::PARAM_STR, 6,000);


            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Get all reviews on a listing
        
        // Delete a Review
        // Update a Review
        
    }
?>