<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Review.php';

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate user object
    $review = new Review($conn);

     // CHECK GET ID PARAMETER OR NOT
     if(isset($_GET['user_id'])){
         //IF HAS ID PARAMETER
         $userID = filter_var($_GET['user_id'], FILTER_VALIDATE_INT,[
             'options' => [
                 'default' => 'user',
                 'min_range' => 1
             ]
         ]);

         if(isset($_GET['listing_id'])){
            //IF HAS ID PARAMETER
            $listingID = filter_var($_GET['listing_id'], FILTER_VALIDATE_INT,[
                'options' => [
                    'default' => 'listing',
                    'min_range' => 1
                ]
            ]);
        }
     }
     else {
         echo json_encode(array('message' => 'No Listing Found with user id '.$userID." and listing id ".$listingID));
     }

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // $review->userID = $data->userID;
    // $review->listingID = $data->listingID;
    $review->expectationScore = $data->expectationScore;
    $review->timeframeScore = $data->timeframeScore;
    $review->budgetScore = $data->budgetScore;
    $review->description = $data->description;

    if($review->create($userID, $listingID)){
        echo json_encode(
            array('Message'=>'Review Created')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Review not Created')
        );
    }
?>