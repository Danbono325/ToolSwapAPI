<?php
    class Review {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // Review Properties
        public $review_id;
        public $expectationScore;
        public $timeframeScore;
        public $budgetScore;
        public $description;


        public function __construct($db) {
            $this->conn = $db;
        }    
        
        // Create Review
        public function create($userID, $listingID){
            $query = "CALL CreateReview(:curUser, :curListing, :expScore, :tfScore, :bScore, :descrip);";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->expectationScore = htmlspecialchars(strip_tags($this->expectationScore));
            $this->timeframeScore = htmlspecialchars(strip_tags($this->timeframeScore));
            $this->budgetScore = htmlspecialchars(strip_tags($this->budgetScore));
            $this->description = htmlspecialchars(strip_tags($this->description));

            //Bind data
            $stmt->bindParam(':curUser', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':curListing', $listingID, PDO::PARAM_INT);
            $stmt->bindParam(':expScore', $this->expectationScore, PDO::PARAM_STR, 3);
            $stmt->bindParam(':tfScore', $this->timeframeScore, PDO::PARAM_STR, 3);
            $stmt->bindParam(':bScore', $this->budgetScore, PDO::PARAM_STR, 3);
            $stmt->bindParam(':descrip', $this->description, PDO::PARAM_STR, 6,000);


            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Review details
        public function readReview() {
            $query = "SELECT * FROM reviews r WHERE idreviews = $this->review_id;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Confirm user review
        public function userReviewConfirm($userID) {
            $query = "SELECT * FROM reviews r JOIN listings_reviews lr ON lr.reviews_idreviews = r.idreviews WHERE lr.users_idusers=$userID AND r.idreviews=$this->review_id LIMIT 0,1";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Get all reviews on a user
        public function readUsersReviews($userID) {
            $query = "SELECT * FROM reviews r 
                        JOIN listings_reviews lr ON lr.reviews_idreviews = r.idreviews 
                        WHERE lr.users_idusers=$userID;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Get all reviews on a listing
        public function readListingReviews($listingID) {
            $query = "SELECT * FROM reviews r 
                        JOIN listings_reviews lr ON lr.reviews_idreviews = r.idreviews 
                        WHERE lr.listings_idlistings = $listingID;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Delete a Review 
        public function delete(){
            //Delete Query
            $query = "CALL DeleteReview($this->review_id);";

            $stmt = $this->conn->prepare($query);


            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Update a Review
        public function update(){
            $query = "UPDATE reviews SET expectationScore = :expectationScore,
                                            timeframeScore = :timeframeScore,
                                            budgetScore = :budgetScore,
                                            description = :description
                                    WHERE idreviews= $this->review_id";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->expectationScore = htmlspecialchars(strip_tags($this->expectationScore));
            $this->timeframeScore = htmlspecialchars(strip_tags($this->timeframeScore));
            $this->budgetScore = htmlspecialchars(strip_tags($this->budgetScore));
            $this->description = htmlspecialchars(strip_tags($this->description));

            //Bind data
            $stmt->bindParam(':expectationScore', $this->expectationScore);
            $stmt->bindParam(':timeframeScore', $this->timeframeScore);
            $stmt->bindParam(':budgetScore', $this->budgetScore);
            $stmt->bindParam(':description', $this->description);

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