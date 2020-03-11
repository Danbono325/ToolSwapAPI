<?php
    class Bid {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // Review Properties
        public $amount; 
        public $estimatedTimeDays;
        public $estimatedTimeWeeks;
        public $estimatedTimeMonths; 
        public $estimatedTimeYears;
        public $message;


        public function __construct($db) {
            $this->conn = $db;
        }
        
        // Post a Bid
        public function create($userID, $curListing){
            $query = "CALL CreateBid(:curUser, :curListing, :amount, 
                                        :estimatedTimeDays, :estimatedTimeWeeks, :estimatedTimeMonths, 
                                        :estimatedTimeYears, :message);";

            $stmt = $this->conn->prepare($query);

            //Clean data
            $this->amount = htmlspecialchars(strip_tags($this->amount));
            // $this->estimatedTimeDays = htmlspecialchars(strip_tags($this->estimatedTimeDays));
            // $this->estimatedTimeWeeks = htmlspecialchars(strip_tags($this->estimatedTimeWeeks));
            // $this->estimatedTimeMonths = htmlspecialchars(strip_tags($this->estimatedTimeMonths));
            // $this->estimatedTimeYears = htmlspecialchars(strip_tags($this->estimatedTimeYears));
            $this->message = htmlspecialchars(strip_tags($this->message));
            

            //Bind data
            $stmt->bindParam(':curUser', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':curListing', $curListing, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $this->amount, PDO::PARAM_INT);
            $stmt->bindParam(':estimatedTimeDays', $this->estimatedTimeDays, PDO::PARAM_INT);
            $stmt->bindParam(':estimatedTimeWeeks', $this->estimatedTimeWeeks, PDO::PARAM_INT);
            $stmt->bindParam(':estimatedTimeMonths', $this->estimatedTimeMonths, PDO::PARAM_INT);
            $stmt->bindParam(':estimatedTimeYears', $this->estimatedTimeYears, PDO::PARAM_INT);
            $stmt->bindParam(':message', $this->message, PDO::PARAM_STR, 6000);

            // Execute Query
            if($stmt->execute()){
                return true;
            }

            //Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }

        // Get Bid Details
        // Get all bids on a Listing
        // Get all bids by a user
        // Update a Bid
        // Delete a Bid

    }
?>