<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Bid.php';
    include_once '../../models/User.php';
    include_once '../../models/Listing.php';


    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate user object
    $bid = new Bid($conn);
    $user = new User($conn);
    $listing = new Listing($conn);


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
       } else {
        echo json_encode(array('message' => 'No Listing Found'));
    }

    }
    else {
        echo json_encode(array('message' => 'No User Found'));
    }

    


    
    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $bid->amount = $data->amount;
    $bid->estimatedTimeDays = $data->estimatedTimeDays;
    $bid->estimatedTimeWeeks = $data->estimatedTimeWeeks;
    $bid->estimatedTimeMonths = $data->estimatedTimeMonths;
    $bid->estimatedTimeYears = $data->estimatedTimeYears;
    $bid->message = $data->message;

    $result = $user->read($userID);
    $result2 = $listing->readListing($listingID);

    if($result->rowCount() <= 0 ){

        echo json_encode(array('Message'=>'No User found with '.$userID));

    } else if ($result2->rowCount() <= 0 ){

        echo json_encode(array('Message'=>'No Listing found with '.$listingID));

    } else if($bid->create($userID, $listingID)){

        echo json_encode(array('Message'=>'Bid Created'));

    } else {
        echo json_encode(
            array('Message'=> 'Bid not Created')
        );
    }

    
?>