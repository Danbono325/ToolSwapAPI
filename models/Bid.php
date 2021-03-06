<?php
    class Bid {
        // DB Stuff
        private $conn;
        //private $table = 'users';

        // Bid Properties\
        public $bid_id;
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
        public function readBid() {
            $query = "SELECT * FROM bids WHERE idbids = $this->bid_id;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Confirm user review
        public function userBidConfirm($userID) {
            $query = "SELECT * FROM bids b JOIN bid_listing bl ON bl.bids_idbids = b.idbids WHERE bl.users_idusers=$userID AND b.idbids =$this->bid_id";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Get all bids on a Listing
        public function readListingBids($listingID) {
            $query = "SELECT * FROM bids b 
                        JOIN bid_listing bl ON bl.bids_idbids = b.idbids 
                        WHERE bl.listings_idlistings = $listingID;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Get all bids by a user
        public function readUsersBids($userID) {
            $query = "SELECT * FROM bids b 
                    JOIN bid_listing bl ON bl.bids_idbids = b.idbids 
                    WHERE bl.users_idusers = $userID;";
        
            //Prepared Statement
            $stmt = $this->conn->prepare($query);

            //Execute
            $stmt->execute();

            return $stmt;
        }

        // Update a Bid
        public function update(){
            $query = "UPDATE bids SET amount = :amount,
                                            estimatedTimeDays = :estimatedTimeDays,
                                            estimatedTimeWeeks = :estimatedTimeWeeks,
                                            estimatedTimeMonths = :estimatedTimeMonths,
                                            estimatedTimeYears = :estimatedTimeYears,
                                            message = :message
                                    WHERE idbids= $this->bid_id";

            $stmt = $this->conn->prepare($query);

            //Clean data
            // $this->title = htmlspecialchars(strip_tags($this->title));
            $this->message = htmlspecialchars(strip_tags($this->message));

            //Bind data
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

        // Delete a Bid
        public function delete(){
            //Delete Query
            $query = "CALL DeleteBid($this->bid_id);";

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