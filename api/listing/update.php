<?php
    // SET HEADER
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    //header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, 
            Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");



    include_once '../../config/Database.php';
    include_once '../../models/Listing.php';

    $database = new Database();
    $conn = $database->dbConnection();

    //Instantiate listing object
    $listing = new Listing($conn);


    // CHECK GET ID PARAMETER OR NOT
    if(isset($_GET['listing_id']))
    {
        //IF HAS ID PARAMETER
        $listingID = filter_var($_GET['listing_id'], FILTER_VALIDATE_INT,[
            'options' => [
                'default' => 'listing',
                'min_range' => 1
            ]
        ]);
    }
    else{
        echo json_encode(array('message' => 'No Listing Found'));
    }

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));


    $listing->title = $data->title;
    $listing->description = $data->description;
    $listing->expectedDays = $data->expectedDays;
    $listing->expectedWeeks = $data->expectedWeeks;
    $listing->expectedMonths = $data->expectedMonths;
    $listing->expectedYears = $data->expectedYears;


    // Update listing
    if($listing->update($listingID)){
        echo json_encode(
            array('Message'=>'Listing Updated')
        );
    } else {
        echo json_encode(
            array('Message'=> 'Listing not Updated')
        );
    }
?>